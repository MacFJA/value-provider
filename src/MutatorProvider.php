<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dev
 * Date: 23/08/2014
 * Time: 18:44
 * To change this template use File | Settings | File Templates.
 */

namespace MacFJA\ValueProvider;


class MutatorProvider implements ProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getValue($object, $propertyName)
    {
        $possibleGetter = array('get' . ucfirst($propertyName), 'is' . ucfirst($propertyName));

        foreach ($possibleGetter as $getter) {
            if (method_exists($object, $getter) || is_callable(array($object, $getter), false)) {
                try {
                    return $object->$getter();
                } catch (\BadFunctionCallException $e) {
                    //Do nothing and continue
                }
                catch (\InvalidArgumentException $e) {
                    //Do nothing and continue
                }
            }
        }

        throw new \InvalidArgumentException(
            'The class "' . get_class($object)
            . '" does not have any getter for the property "' . $propertyName
            . '" (tried getters: "' . implode('", "', $possibleGetter) . '")'
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function setValue(&$object, $propertyName, $value)
    {
        $possibleSetter = array('set' . ucfirst($propertyName));

        foreach ($possibleSetter as $setter) {
            if (method_exists($object, $setter) || is_callable(array($object, $setter), false)) {
                try {
                    $object->$setter($value);
                    return $object;
                } catch (\BadFunctionCallException $e) {
                    //Do nothing and continue
                }
                catch (\InvalidArgumentException $e) {
                    //Do nothing and continue
                }
            }
        }

        throw new \InvalidArgumentException(
            'The class "' . get_class($object) . '"' .
            ' does not have any setter for the property "' . $propertyName . '"' .
            ' (tried setters: "' . implode('", "', $possibleSetter) . '")'
        );
    }
}