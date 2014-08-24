<?php

namespace MacFJA\ValueProvider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class MetadataProvider
 *
 * Use Doctrine Metadata to read/write object (Entity) values.
 *
 * @author MacFJA
 * @package MacFJA\ValueProvider
 */
class MetadataProvider implements ProviderInterface
{
    /**
     * @var EntityManager
     */
    protected static $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public static function setEntityManager($entityManager)
    {
        self::$entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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