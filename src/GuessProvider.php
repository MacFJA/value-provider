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
 * @package MacFJA\ValueProvider
 * @author  MacFJA
 * @license MIT
 */
class GuessProvider extends ChainProvider
{
    /**
     * Get the ChainProvider instance
     *
     * @return void
     */
    protected static function ensureInitialize()
    {
        if (count(self::$providers) === 0) {
            self::setProviders(array(
                'MacFJA\ValueProvider\MutatorProvider',
                'MacFJA\ValueProvider\PropertyProvider',
                'MacFJA\ValueProvider\ReflectorProvider'
            ));
        }
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
        self::ensureInitialize();
        return parent::getValue($object, $propertyName);
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
        self::ensureInitialize();
        return parent::setValue($object, $propertyName, $value);
    }
}
