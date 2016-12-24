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
    private $repositoryKeys;

    /**
     * @param ContainerInterface $container
     * @param array              $repositoryKeys
     */
    public function __construct(ContainerInterface $container, array $repositoryKeys)
    {
        $this->container = $container;
        $this->repositoryKeys = $repositoryKeys;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $modelClass = array_shift($arguments);

        return $this->getRepositoryByClass($modelClass)->$name(...$arguments);
    }

    /**
     * @param string $modelClass
     * @param string|null $id
     *
     * @return ModelInterface|null
     */
    public function find(string $modelClass, string $id = null)
    {
        if (null === $id) {
            return null;
        }

        return $this->getRepositoryByClass($modelClass)->find($id);
    }

    /**
     * @param string     $modelClass
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return ModelInterface|null
     */
    public function findOneBy(string $modelClass, array $criteria, array $orderBy = null)
    {
        return $this->getRepositoryByClass($modelClass)->findOneBy($criteria, $orderBy);
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
        foreach ($this->repositoryKeys as $repositoryKey) {
            /** @var RepositoryInterface $repository */
            $repository = $this->container->get($repositoryKey);
            if ($repository->isResponsible($modelClass)) {
                return $repository;
            }
        }

        throw MissingRepositoryException::create($modelClass);
    }
}
