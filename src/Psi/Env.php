<?php

namespace Psi;

final class Env
{
    /**
     * Reads all environment paramters with the given prefix.
     *
     * Any value with the given prefix will be returned. Its name will be converted:
     * * Any __ (double underscore) will be replaced by a . (dot).
     * * The prefix will be removed from the parameter's name.
     *
     * @param string $prefix
     *
     * @return array
     */
    public static function getParameters($prefix)
    {
        $parameters = array_filter($_SERVER, function ($key) use ($prefix) {
            return 0 === strpos($key, $prefix);
        }, ARRAY_FILTER_USE_KEY);

        $length = strlen($prefix);

        return array_combine(array_map(function ($key) use ($length) {
            return strtolower(str_replace('__', '.', substr($key, $length)));
        }, array_keys($parameters)), $parameters);
    }
}
