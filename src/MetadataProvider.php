<?php

namespace MacFJA\ValueProvider;

use Doctrine\ORM\EntityManager;

/**
 * Class MetadataProvider
 *
 * Use Doctrine Metadata to read/write object (Entity) values.
 *
 * @package MacFJA\ValueProvider
 * @author  MacFJA
 * @license MIT
 */
class MetadataProvider implements ProviderInterface
{
    /**
     * The Doctrine entity manager
     *
     * @var EntityManager
     */
    protected static $entityManager;

    /**
     * Set the entityManager of the application
     *
     * @param \Doctrine\ORM\EntityManager $entityManager The Doctrine entity manager
     *
     * @return void
     */
    public static function setEntityManager($entityManager)
    {
        self::$entityManager = $entityManager;
    }

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
        $metadata = self::$entityManager->getClassMetadata(get_class($object));

        if (!$metadata->hasField($propertyName)) {
            throw new \InvalidArgumentException(
                'The class "' . get_class($object) . '" does not have the property "' . $propertyName . '"'
            );
        }

        return $metadata->getFieldValue($object, $propertyName);
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
        $metadata = self::$entityManager->getClassMetadata(get_class($object));

        if (!$metadata->hasField($propertyName)) {
            throw new \InvalidArgumentException(
                'The class "' . get_class($object) . '" does not have the property "' . $propertyName . '"'
            );
        }

        $metadata->setFieldValue($object, $propertyName, $value);
        return $object;
    }
}
