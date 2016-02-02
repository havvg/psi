<?php

namespace Psi\DependencyInjection\Extension;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * An enabled extension will be loaded by the container regardless a configuration being present.
 *
 * This interface should be used on extensions, which have a sensible & complete default configuration.
 * The extension may be disabled by user request.
 *
 * @see \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition::canBeDisabled
 */
interface EnabledExtensionInterface extends ExtensionInterface
{
}
