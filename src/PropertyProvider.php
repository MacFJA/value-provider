<?php

namespace MacFJA\ValueProvider;

/**
 * Class PropertyProvider
 *
 * Use PropertyProvider to read/write object values.
 *
 * @author MacFJA
 * @package MacFJA\ValueProvider
 */
class PropertyProvider implements ProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getValue($object, $propertyName)
    {


        if (property_exists($object, $propertyName) || isset($object->$propertyName)) {
            try {
                return $object->$propertyName;
            } catch (\BadFunctionCallException $e) {
                //Do nothing and continue
            }
            catch (\InvalidArgumentException $e) {
                //Do nothing and continue
            }
        }


        throw new \InvalidArgumentException(
            'The class "' . get_class($object) . '" does not have the property "' . $propertyName . '" or it\'s not readable'
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function setValue(&$object, $propertyName, $value)
    {

        if (property_exists($object, $propertyName) || isset($object->$propertyName)) {
            try {
                $object->$propertyName = $value;
                return $object;
            } catch (\BadFunctionCallException $e) {
                //Do nothing and continue
            }
            catch (\InvalidArgumentException $e) {
                //Do nothing and continue
            }
        }

        throw new \InvalidArgumentException(
            'The class "' . get_class($object) . '" does not have the property "' . $propertyName . '" or it\'s not writable'
        );
    }
}