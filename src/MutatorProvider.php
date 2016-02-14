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
 * @package MacFJA\ValueProvider
 * @author  MacFJA
 * @license MIT
 */
class MutatorProvider implements ProviderInterface
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
        $possibleGetter = array('get' . ucfirst($propertyName), 'is' . ucfirst($propertyName));

        /*
         * This case handle both $URL, and $xPosition case.
         * The getter for $xPosition is getxPosition because of the implementation of
         * "java.beans.Introspector.decapitalize".
         * {@see http://stackoverflow.com/a/16146215}
         * {@see http://dertompson.com/2013/04/29/java-bean-getterssetters/}
         */
        if (substr($propertyName, 1, 1) !== strtolower(substr($propertyName, 1, 1))) {
            $possibleGetter[] = 'get' . $propertyName;
            $possibleGetter[] = 'is' . $propertyName;
        }

        foreach ($possibleGetter as $getter) {
            if (method_exists($object, $getter) || is_callable(array($object, $getter), false)) {
                try {
                    return call_user_func(array($object, $getter));
                    // @codingStandardsIgnoreLine
                } catch (\BadFunctionCallException $e) {
                    //Do nothing and continue
                    // @codingStandardsIgnoreLine
                } catch (\InvalidArgumentException $e) {
                    //Do nothing and continue
                }
            }
        }

        throw new \InvalidArgumentException(
            vsprintf(
                'The class "%s" does not have any getter for the property "%s" (tried getters: "%s")',
                array(get_class($object), $propertyName, implode('", "', $possibleGetter))
            )
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
        $possibleSetter = array('set' . ucfirst($propertyName));

        /*
         * This case handle both $URL, and $xPosition case.
         * The setter for $xPosition is setxPosition because of the implementation of
         * "java.beans.Introspector.decapitalize".
         * {@see http://stackoverflow.com/a/16146215}
         * {@see http://dertompson.com/2013/04/29/java-bean-getterssetters/}
         */
        if (substr($propertyName, 1, 1) !== strtolower(substr($propertyName, 1, 1))) {
            $possibleSetter[] = 'set' . $propertyName;
        }

        foreach ($possibleSetter as $setter) {
            if (method_exists($object, $setter) || is_callable(array($object, $setter), false)) {
                try {
                    $return = call_user_func(array($object, $setter), $value);
                    return $return;
                    // @codingStandardsIgnoreLine
                } catch (\BadFunctionCallException $e) {
                    //Do nothing and continue
                    // @codingStandardsIgnoreLine
                } catch (\InvalidArgumentException $e) {
                    //Do nothing and continue
                }
            }
        }

        throw new \InvalidArgumentException(
            vsprintf(
                'The class "%s" does not have any setter for the property "%s" (tried setters: "%s")',
                array(get_class($object), $propertyName, implode('", "', $possibleSetter))
            )
        );
    }
}
