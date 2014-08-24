<?php

namespace MacFJA\ValueProvider;

/**
 * Class ProviderInterface
 *
 * @author MacFJA
 * @package MacFJA\ValueProvider
 */
interface ProviderInterface
{
    /**
     * Get a property value
     * @param mixed $object
     * @param string $propertyName
     * @return mixed
     * @throws \InvalidArgumentException if the property doesn't exist or can not be read
     */
    public static function getValue($object, $propertyName);

    /**
     * Set the value of a property
     * @param mixed $object
     * @param string $propertyName
     * @param mixed $value
     * @return mixed The updated object
     * @throws \InvalidArgumentException if the property doesn't exist or can not be write
     */
    public static function setValue(&$object, $propertyName, $value);
}