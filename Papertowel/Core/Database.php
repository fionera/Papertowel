<?php

namespace Papertowel\Core;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

class Database
{

    /** @var EntityManager $entityManager */
    private $entityManager;

    /**
     * Database constructor.
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct()
    {
        $config = Setup::createConfiguration(true);
        $driver = new AnnotationDriver(new AnnotationReader(), [__DIR__ . '/../Models']);

        // registering noop annotation autoloader - allow all annotations by default
        AnnotationRegistry::registerLoader('class_exists');
        $config->setMetadataDriverImpl($driver);

        // database configuration parameters
        $conn = array(
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/db.sqlite',
        );

        $this->entityManager = EntityManager::create($conn, $config);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @param $entityName string
     * @return EntityRepository
     */
    public function getRepository($entityName): EntityRepository
    {
        return $this->entityManager->getRepository($entityName);
    }
}