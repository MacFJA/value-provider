<?php

namespace MacFJA\ValueProvider;

/**
 * Class PropertyProvider
 *
 * Use PropertyProvider to read/write object values.
 *
 * @package MacFJA\ValueProvider
 * @author  MacFJA
 * @license MIT
 */
class PropertyProvider implements ProviderInterface
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


        if (property_exists($object, $propertyName) || isset($object->$propertyName)) {
            try {
                return $object->$propertyName;
                // @codingStandardsIgnoreLine
            } catch (\BadFunctionCallException $e) {
                //Do nothing and continue
                // @codingStandardsIgnoreLine
            } catch (\InvalidArgumentException $e) {
                //Do nothing and continue
            }
        }


        throw new \InvalidArgumentException(
            vsprintf('The class "%s" does not have the property "%s" or it\'s not readable', array(
                get_class($object),
                $propertyName
            ))
        );
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

        if (property_exists($object, $propertyName) || isset($object->$propertyName)) {
            try {
                $object->$propertyName = $value;
                return $object;
                // @codingStandardsIgnoreLine
            } catch (\BadFunctionCallException $e) {
                //Do nothing and continue
                // @codingStandardsIgnoreLine
            } catch (\InvalidArgumentException $e) {
                //Do nothing and continue
            }
        }

        throw new \InvalidArgumentException(
            vsprintf('The class "%s" does not have the property "%s" or it\'s not writable', array(
                get_class($object),
                $propertyName
            ))
        );
    }
}
