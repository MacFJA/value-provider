<?php


namespace MacFJA\ValueProvider;

/**
 * Class ReflectorProvider
 *
 * Use PHP Class and Property reflector to read/write object values.
 * (Try to temporary change property accessibility if needed)
 *
 * @author MacFJA
 * @package MacFJA\ValueProvider
 */
class ReflectorProvider implements ProviderInterface
{

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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