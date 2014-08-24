<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dev
 * Date: 17/08/14
 * Time: 17:09
 * To change this template use File | Settings | File Templates.
 */

namespace MacFJA\ValueProvider;


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