<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

use Interop\Container\ContainerInterface;

final class Resolver implements ResolverInterface
{
    /**
     * @var ContainerInterface|RepositoryInterface[]
     */
    private $container;

    /**
     * @var string[]|array
     */
    private $mapping;

    /**
     * @param ContainerInterface $container
     * @param array              $mapping
     */
    public function __construct(ContainerInterface $container, array $mapping)
    {
        $this->container = $container;
        $this->mapping = $mapping;
    }

    /**
     * @param string $modelClass
     * @param string $id
     *
     * @return ModelInterface|null
     */
    public function find(string $modelClass, string $id)
    {
        return $this->getRepositoryByClass($modelClass)->find($id);
    }

    /**
     * @param string $modelClass
     * @param array  $criteria
     *
     * @return ModelInterface|null
     */
    public function findOneBy(string $modelClass, array $criteria)
    {
        return $this->getRepositoryByClass($modelClass)->findOneBy($criteria);
    }

    /**
     * @param string     $modelClass
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array
     */
    public function findBy(
        string $modelClass,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null
    ): array {
        return $this->getRepositoryByClass($modelClass)->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param string $modelClass
     * @param string $id
     *
     * @return \Closure
     */
    public function lazyFind(string $modelClass, string $id): \Closure
    {
        return function () use ($modelClass, $id) {
            return $this->find($modelClass, $id);
        };
    }

    /**
     * @param string $modelClass
     * @param array  $criteria
     *
     * @return \Closure
     */
    public function lazyFindOneBy(string $modelClass, array $criteria): \Closure
    {
        return function () use ($modelClass, $criteria) {
            return $this->findOneBy($modelClass, $criteria);
        };
    }

    /**
     * @param string     $modelClass
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return \Closure
     */
    public function lazyFindBy(
        string $modelClass,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null
    ): \Closure {
        return function () use ($modelClass, $criteria, $orderBy, $limit, $offset) {
            return $this->findBy($modelClass, $criteria, $orderBy, $limit, $offset);
        };
    }

    /**
     * @param ModelInterface $model
     */
    public function persist(ModelInterface $model)
    {
        $this->getRepositoryByClass(get_class($model))->persist($model);
    }

    /**
     * @param ModelInterface $model
     */
    public function remove(ModelInterface $model)
    {
        $this->getRepositoryByClass(get_class($model))->remove($model);
    }

    /**
     * @param string $modelClass
     *
     * @return RepositoryInterface
     */
    private function getRepositoryByClass(string $modelClass): RepositoryInterface
    {
        if (!isset($this->mapping[$modelClass])) {
            throw MissingRepositoryException::create($modelClass);
        }

        return $this->container->get($this->mapping[$modelClass]);
    }
}
