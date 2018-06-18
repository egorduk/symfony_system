<?php

namespace SecureBundle\Factory;

use Doctrine\ORM\Repository\RepositoryFactory;
use Doctrine\ORM\EntityManagerInterface;

class EntityRepositoryFactory implements RepositoryFactory
{
    /**
     * The list of EntityRepository instances.
     *
     * @var array<\Doctrine\Common\Persistence\ObjectRepository>
     */
    private $repositoryList = [];
    private $repositoryClassMap = [];

    public function __construct($repositoryClassMap)
    {
        if (null !== $repositoryClassMap) {
            $this->repositoryClassMap = $repositoryClassMap;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $entityName = ltrim($entityName, '\\');

        if (isset($this->repositoryList[$entityName])) {
            return $this->repositoryList[$entityName];
        }

        $repository = $this->createRepository($entityManager, $entityName);

        $this->repositoryList[$entityName] = $repository;

        return $repository;
    }

    /**
     * Create a new repository instance for an entity class.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager The EntityManager instance.
     * @param string                               $entityName    The name of the entity.
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function createRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $metadata = $entityManager->getClassMetadata($entityName);
        $repositoryClassName = $metadata->customRepositoryClassName;

        if ($repositoryClassName === null) {
            if (isset($this->repositoryClassMap[$metadata->rootEntityName])) {
                $repositoryClassName = $this->repositoryClassMap[$metadata->rootEntityName];
            } else {
                $configuration       = $entityManager->getConfiguration();
                $repositoryClassName = $configuration->getDefaultRepositoryClassName();
            }
        }

        return new $repositoryClassName($entityManager, $metadata);
    }
}
