<?php

namespace MacFJA\ValueProvider;

/**
 * Class MutatorProvider
 *
 * Use Mutator (Getter and Setter) to read/write object values.<br/>
 * Two getter are tried:
 * <ul>
 *     <li><b><tt>get</tt></b><tt>PropertyName</tt></li>
 *     <li><b><tt>is</tt></b><tt>PropertyName</tt></li>
 * </ul>
 *
 * @author MacFJA
 * @package MacFJA\ValueProvider
 */
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