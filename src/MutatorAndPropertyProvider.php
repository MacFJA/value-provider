<?php

namespace MacFJA\ValueProvider;

/**
 * Class MutatorAndPropertyProvider
 *
 * Use MutatorProvider and PropertyProvider to read/write object values.<br/>
 * First try to use MutatorProvider and, if fail, try to use PropertyProvider.
 *
 * @package    MacFJA\ValueProvider
 * @author     MacFJA
 * @license    MIT
 * @see        MacFJA\ValueProvider\GuessProvider
 * @deprecated 0.2.0 Prefer the provider MacFJA\ValueProvider\GuessProvider
 */
class MutatorAndPropertyProvider implements ProviderInterface
{

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
        try {
            return MutatorProvider::getValue($object, $propertyName);
        } catch (\InvalidArgumentException $e) {
            return PropertyProvider::getValue($object, $propertyName);
        }
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
        try {
            return MutatorProvider::setValue($object, $propertyName, $value);
        } catch (\InvalidArgumentException $e) {
            return PropertyProvider::setValue($object, $propertyName, $value);
        }
    }
}
