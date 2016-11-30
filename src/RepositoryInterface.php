<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

interface RepositoryInterface
{
    /**
     * @param string $modelClass
     *
     * @return bool
     */
    public function isResponsible(string $modelClass): bool;

    /**
     * @param string $id
     *
     * @return ModelInterface|null
     */
    public function find(string $id);

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return ModelInterface|null
     */
    public function findOneBy(array $criteria, array $orderBy = null);

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return ModelInterface[]|array
     */
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array;

    /**
     * @param ModelInterface $model
     */
    public function persist(ModelInterface $model);

    /**
     * @param ModelInterface $model
     */
    public function remove(ModelInterface $model);
}
