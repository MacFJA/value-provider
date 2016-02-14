<?php

namespace MacFJA\ValueProvider;

/**
 * Class ChainProvider
 *
 * Try to get/set the property with the help of multiple provider.
 *
 * @package MacFJA\ValueProvider
 * @author  MacFJA
 * @license MIT
 */
class ChainProvider
{
    /**
     * List of provider to use.
     * The order of the list is order which will be used to get/set value
     *
     * @var string[]
     */
    protected static $providers = array();

    /**
     * Get the list of providers
     *
     * @return string[]
     */
    public static function getProviders()
    {
        return self::$providers;
    }

    /**
     * Set the list of provider to use
     *
     * @param string[] $providers The list of provider class name
     *
     * @return void
     */
    public static function setProviders($providers)
    {
        self::$providers = $providers;
    }

    /**
     * Get a property value
     *
     * @param mixed  $object       The object to read
     * @param string $propertyName The name of the property to read
     *
     * @return mixed
     * @throws \InvalidArgumentException if the property doesn't exist or can not be read
     */
    public static function getValue($object, $propertyName)
    {
        /**
         * The error container
         *
         * @var \InvalidArgumentException|null $error
         */
        $error = null;

        foreach (self::$providers as $provider) {
            $error = null;
            $value = self::tryGetValue($provider, $object, $propertyName, $error);
            if (is_null($error)) {
                return $value;
            }
        }

        throw $error;
    }

    /**
     * Set the value of a property
     *
     * @param mixed  $object       The object to write
     * @param string $propertyName The name of the property to set
     * @param mixed  $value        The new value
     *
     * @return mixed The updated object
     * @throws \InvalidArgumentException if the property doesn't exist or can not be write
     */
    public static function setValue(&$object, $propertyName, $value)
    {
        /**
         * The error container
         *
         * @var \InvalidArgumentException|null $error
         */
        $error = null;

        foreach (self::$providers as $provider) {
            $error  = null;
            $result = self::trySetValue($provider, $object, $propertyName, $value, $error);
            if (is_null($error)) {
                return $result;
            }
        }

        throw $error;
    }

    /**
     * Try to get the value of an object property with a specific provider
     *
     * @param string|object                   $provider     The provider class(name) to use
     * @param object                          $object       The object to read
     * @param string                          $propertyName The name of the property to read
     * @param \InvalidArgumentException|mixed $error        <p>
     *     If an error occurs, this variable will be set to the error triggered
     *   </p>
     *
     * @return mixed|null <p>
     *   The value of the object property, or <tt>null</tt> and an error in <tt>$error</tt> if an error occur.
     * </p>
     */
    protected static function tryGetValue($provider, $object, $propertyName, &$error)
    {
        try {
            return call_user_func(array($provider, 'getValue'), $object, $propertyName);
        } catch (\InvalidArgumentException $e) {
            $error = $e;
            return null;
        }
    }

    /**
     * Try to set the value of an object property with a specific provider
     *
     * @param string|object                   $provider     The provider class(name) to use
     * @param object                          $object       The object to write
     * @param string                          $propertyName The name of the property to write
     * @param mixed                           $value        The value to set
     * @param \InvalidArgumentException|mixed $error        <p>
     *     If an error occurs, this variable will be set to the error triggered
     *   </p>
     *
     * @return mixed|null The object, or <tt>null</tt> and an error in <tt>$error</tt> if an error occur.
     */
    protected static function trySetValue($provider, &$object, $propertyName, $value, &$error)
    {
        try {
            return call_user_func(array($provider, 'setValue'), $object, $propertyName, $value);
        } catch (\InvalidArgumentException $e) {
            $error = $e;
            return null;
        }
    }
}
