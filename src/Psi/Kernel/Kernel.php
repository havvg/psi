<?php

namespace Psi\Kernel;

use Psi\DependencyInjection\Compiler\MergeEnabledExtensionsPass;
use Psi\Env;
use Psi\Exception\KernelNotBootedException;
use Psi\Plugin\Command\CommandPlugin;
use Psi\Plugin\EventDispatcher\EventDispatcherPlugin;
use Psi\Plugin\PluginInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class Kernel implements CacheAwareKernelInterface
{
    const ENV_PREFIX = 'PSI__';
    const VERSION = '1.0.0';

    /**
     * @var string
     */
    private $environment;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ConfigCacheInterface
     */
    private $configCache;

    /**
     * Constructor.
     *
     * @param string $environment
     * @param bool   $debug
     */
    public function __construct($environment, $debug = false)
    {
        $this->environment = $environment;
        $this->debug = $debug;

        $this->configCache = new ConfigCache($this->getCacheDir().DIRECTORY_SEPARATOR.'CachedContainer.php', $this->debug);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }

        if (!$this->configCache->isFresh()) {
            $this->container = $this->createContainerBuilder();
            $this->container->compile();

            $dumper = new PhpDumper($this->container);
            $cache = $dumper->dump([
                'namespace' => __NAMESPACE__,
                'class' => 'CachedContainer',
                'file' => $this->configCache->getPath(),
            ]);

            $this->configCache->write($cache, $this->container->getResources());
        }

        require_once $this->configCache->getPath();

        $this->container = new CachedContainer();

        $this->booted = true;
    }

    /**
     * Replaces the configuration cache this kernel uses.
     *
     * @param ConfigCacheInterface $configCache
     *
     * @return $this
     */
    public function setConfigCache(ConfigCacheInterface $configCache)
    {
        $this->configCache = $configCache;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContainer()
    {
        if ($this->booted) {
            return $this->container;
        }

        throw new KernelNotBootedException('The container cannot be retrieved. The kernel has not been booted.');
    }

    /**
     * {@inheritdoc}
     */
    public function getRootDir()
    {
        if (null === $this->rootDir) {
            $reflection = new \ReflectionObject($this);
            $this->rootDir = dirname($reflection->getFileName());
        }

        return $this->rootDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        $env = Env::getParameters(static::ENV_PREFIX);
        if (!empty($env['kernel.cache_dir'])) {
            return $env['kernel.cache_dir'];
        }

        return implode(DIRECTORY_SEPARATOR, [
            $this->getRootDir(), 'var', 'cache', $this->environment,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'App';
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return static::VERSION;
    }

    /**
     * Returns the environment of this kernel.
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Creates a container builder to be used within the kernel.
     *
     * @return ContainerBuilder
     */
    protected function createContainerBuilder()
    {
        $container = new ContainerBuilder(new ParameterBag($this->getParameters()));

        $this->registerPlugins($container);
        $this->registerContainerConfiguration($this->getContainerLoader($container));

        $container->addObjectResource($this);

        $container->getCompilerPassConfig()->setMergePass(new MergeEnabledExtensionsPass());

        return $container;
    }

    /**
     * Registers plugins with the given container.
     *
     * @see PluginInterface
     * @see Kernel::getPlugins
     *
     * @param ContainerBuilder $container
     */
    protected function registerPlugins(ContainerBuilder $container)
    {
        foreach ($this->getPlugins() as $plugin) {
            $plugin->build($container);
        }
    }

    /**
     * Returns a list of plugins to be registered.
     *
     * @see Kernel::registerPlugins
     *
     * @return PluginInterface[]
     */
    public function getPlugins()
    {
        return [
            new CommandPlugin(),
            new EventDispatcherPlugin(),
        ];
    }

    /**
     * Creates a configuration loader to be used when building the container.
     *
     * @see Kernel::registerContainerConfiguration
     *
     * @param ContainerBuilder $container
     *
     * @return LoaderInterface
     */
    protected function getContainerLoader(ContainerBuilder $container)
    {
        $locator = new FileLocator($this->getRootDir());
        $resolver = new LoaderResolver([
            new ClosureLoader($container),
            new DirectoryLoader($container, $locator),
            new IniFileLoader($container, $locator),
            new PhpFileLoader($container, $locator),
            new XmlFileLoader($container, $locator),
            new YamlFileLoader($container, $locator),
        ]);

        return new DelegatingLoader($resolver);
    }

    /**
     * Registers the container configuration with the given loader.
     *
     * @see Kernel::getContainerLoader
     *
     * @param LoaderInterface $loader
     */
    protected function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(implode(DIRECTORY_SEPARATOR, [
            $this->getRootDir(), 'config', 'services',
        ]), 'directory');

        $loader->load(implode(DIRECTORY_SEPARATOR, [
            $this->getRootDir(), 'config', 'environments', $this->environment,
        ]), 'directory');
    }

    /**
     * Returns a list of parameters provided by the kernel itself.
     *
     * Those parameters are available within the dependency injection container.
     *
     * @return array
     */
    protected function getParameters()
    {
        return array_merge([
            'kernel.root_dir' => realpath($this->getRootDir()) ?: $this->getRootDir(),
            'kernel.cache_dir' => realpath($this->getCacheDir()) ?: $this->getCacheDir(),
            'kernel.environment' => $this->environment,
            'kernel.debug' => $this->debug,
        ], Env::getParameters(static::ENV_PREFIX));
    }
}
