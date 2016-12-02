<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

interface ResolverInterface
{
    /**
     * @param string $modelClass
     * @param string $id
     *
     * @return ModelInterface|null
     */
    public function find(string $modelClass, string $id);

    /**
     * @param string     $modelClass
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return ModelInterface|null
     */
    public function findOneBy(string $modelClass, array $criteria, array $orderBy = null);

    /**
     * @param string     $modelClass
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return ModelInterface[]|array
     */
    public function findBy(
        string $modelClass,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null
    ): array;

    /**
     * @param string $modelClass
     * @param string $id
     *
     * @return \Closure
     */
    public function lazyFind(string $modelClass, string $id): \Closure;

    /**
     * @param string     $modelClass
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return \Closure
     */
    public function lazyFindOneBy(string $modelClass, array $criteria, array $orderBy = null): \Closure;

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
    ): \Closure;

    /**
     * @param ModelInterface $model
     */
    public function persist(ModelInterface $model);

    /**
     * @param ModelInterface $model
     */
    public function remove(ModelInterface $model);
}
