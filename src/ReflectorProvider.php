<?php

namespace MacFJA\ValueProvider;

/**
 * Class ReflectorProvider
 *
 * Use PHP Class and Property reflector to read/write object values.
 * (Try to temporary change property accessibility if needed)
 *
 * @package MacFJA\ValueProvider
 * @author  MacFJA
 * @license MIT
 */
class ReflectorProvider implements ProviderInterface
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
        $classReflection = new \ReflectionClass(get_class($object));
        if (!$classReflection->hasProperty($propertyName)) {
            throw new \InvalidArgumentException(
                'The class "' . get_class($object) . '" does not have the property "' . $propertyName . '"'
            );
        }
        $propertyReflection = $classReflection->getProperty($propertyName);
        if ($propertyReflection->isPublic()) {
            return $propertyReflection->getValue($object);
        }

        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            $propertyReflection->setAccessible(true);
            $value = $propertyReflection->getValue($object);
            $propertyReflection->setAccessible(false);
            return $value;
        }

        throw new \InvalidArgumentException(
            'The property "' . $propertyName . '" of the class "' . get_class($object) . '" is not readable'
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
        $classReflection = new \ReflectionClass(get_class($object));
        if (!$classReflection->hasProperty($propertyName)) {
            throw new \InvalidArgumentException(
                'The class "' . get_class($object) . '" does not have the property "' . $propertyName . '"'
            );
        }
        $propertyReflection = $classReflection->getProperty($propertyName);
        if ($propertyReflection->isPublic()) {
            $propertyReflection->setValue($object, $value);
            return $object;
        }

        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            $propertyReflection->setAccessible(true);
            $propertyReflection->setValue($object, $value);
            $propertyReflection->setAccessible(false);
            return $object;
        }

        throw new \InvalidArgumentException(
            'The property "' . $propertyName . '" of the class "' . get_class($object) . '" is not writable'
        );
    }
}
