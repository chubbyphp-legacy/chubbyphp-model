<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

interface ResolverInterface
{
    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments);

    /**
     * @param string $modelClass
     * @param string|null $id
     *
     * @return ModelInterface|null
     */
    public function find(string $modelClass, string $id = null);

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
     * @param ModelInterface $model
     */
    public function persist(ModelInterface $model);

    /**
     * @param ModelInterface $model
     */
    public function remove(ModelInterface $model);
}
