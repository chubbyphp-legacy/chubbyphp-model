<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

use Chubbyphp\Model\Doctrine\DBAL\MissingRepositoryException;
use Interop\Container\ContainerInterface;

final class Resolver implements ResolverInterface
{
    /**
     * @var ContainerInterface|RepositoryInterface[]
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $modelClass
     * @param string $id
     * @return \Closure
     */
    public function find(string $modelClass, string $id): \Closure
    {
        return function () use ($modelClass, $id) {
            return $this->getRepositoryByClass($modelClass)->find($id);
        };
    }

    /**
     * @param string $modelClass
     * @param array $criteria
     * @return \Closure
     */
    public function findOneBy(string $modelClass, array $criteria): \Closure
    {
        return function () use ($modelClass, $criteria) {
            return $this->getRepositoryByClass($modelClass)->findOneBy($criteria);
        };
    }

    /**
     * @param string $modelClass
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return \Closure
     */
    public function findBy(
        string $modelClass,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null
    ): \Closure {
        return function () use ($modelClass, $criteria, $orderBy, $limit, $offset) {
            return $this->getRepositoryByClass($modelClass)->findBy($criteria, $orderBy, $limit, $offset);
        };
    }

    /**
     * @param string $modelClass
     * @return RepositoryInterface
     */
    public function getRepositoryByClass(string $modelClass): RepositoryInterface
    {
        if (!$this->container->has($modelClass)) {
            throw MissingRepositoryException::create($modelClass);
        }

        return $this->container->get($modelClass);
    }
}
