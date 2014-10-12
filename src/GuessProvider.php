<?php


namespace MacFJA\ValueProvider;

/**
 * Class GuessProvider
 *
 * Try to get/set the property with the help of multiple provider.
 * Try, in this order, the following providers :<ul>
 * <li>MacFJA\ValueProvider\MutatorProvider</li>
 * <li>MacFJA\ValueProvider\PropertyProvider</li>
 * <li>MacFJA\ValueProvider\ReflectorProvider</li></ul>
 *
 * @author MacFJA
 * @package MacFJA\ValueProvider
 */
class GuessProvider implements ProviderInterface
{
    const PROVIDER_LIST = 'MacFJA\ValueProvider\MutatorProvider,MacFJA\ValueProvider\PropertyProvider,MacFJA\ValueProvider\ReflectorProvider';

    /**
     * {@inheritdoc}
     */
    public static function getValue($object, $propertyName)
    {
        $providers = explode(',', self::PROVIDER_LIST);
        /** @var \InvalidArgumentException|null $error */
        $error = null;

        foreach ($providers as $provider) {
            $error = null;
            $value = self::tryGetValue($provider, $object, $propertyName, $error);
            if (is_null($error)) {
                return $value;
            }
        }

        throw $error;
    }

    /**
     * {@inheritdoc}
     */
    public static function setValue(&$object, $propertyName, $value)
    {
        $providers = explode(',', self::PROVIDER_LIST);
        /** @var \InvalidArgumentException|null $error */
        $error = null;

        foreach ($providers as $provider) {
            $error = null;
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
     * @param string|object $provider The provider class(name) to use
     * @param object $object The object to read
     * @param string $propertyName The name of the property to read
     * @param \InvalidArgumentException|mixed $error If an error occurs, this variable will be set to the error triggered
     * @return mixed|null The value of the object property, or <tt>null</tt> and an error in <tt>$error</tt> if an error occur.
     */
    protected static function tryGetValue($provider, $object, $propertyName, &$error)
    {
        if (is_object($provider)) {
            $provider = get_class($provider);
        }

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
     * @param string|object $provider The provider class(name) to use
     * @param object $object The object to write
     * @param string $propertyName The name of the property to write
     * @param mixed $value The value to set
     * @param \InvalidArgumentException|mixed $error If an error occurs, this variable will be set to the error triggered
     * @return mixed|null The object, or <tt>null</tt> and an error in <tt>$error</tt> if an error occur.
     */
    protected static function trySetValue($provider, &$object, $propertyName, $value, &$error)
    {
        if (is_object($provider)) {
            $provider = get_class($provider);
        }

        try {
            return call_user_func(array($provider, 'setValue'), $object, $propertyName, $value);
        } catch (\InvalidArgumentException $e) {
            $error = $e;
            return null;
        }
    }
}