<?php

namespace App\Application;

/**
 *
 */
class Configure
{
    private static array $configurations;

    /**
     * Sets a configuration value for the specified key.
     *
     * @param string $key The key for which the configuration value should be set.
     * @param mixed $value The value to associate with the given key.
     * @return void
     */
    static function set(string $key, mixed $value): void
    {
        self::$configurations[$key] = $value;
    }

    /**
     * Retrieves a configuration value based on the provided key.
     *
     * @param string $key The key corresponding to the configuration value to be retrieved.
     * @return mixed The configuration value associated with the given key.
     */
    static function get(string $key): mixed
    {
        // todo: déclencher une exception
//        if (empty(self::$configurations)) {
//
//        }
        return self::$configurations[$key];
    }
}
