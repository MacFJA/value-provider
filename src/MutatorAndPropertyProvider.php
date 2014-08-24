<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dev
 * Date: 23/08/2014
 * Time: 21:16
 * To change this template use File | Settings | File Templates.
 */

namespace MacFJA\ValueProvider;


class MutatorAndPropertyProvider implements ProviderInterface
{

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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