<?php

namespace MacFJA\ValueProvider;

/**
 * Class MutatorAndPropertyProvider
 *
 * Use MutatorProvider and PropertyProvider to read/write object values.<br/>
 * First try to use MutatorProvider and, if fail, try to use PropertyProvider.
 *
 * @deprecated 0.2.0 Prefer the provider MacFJA\ValueProvider\GuessProvider
 * @see MacFJA\ValueProvider\GuessProvider
 * @author MacFJA
 * @package MacFJA\ValueProvider
 */
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