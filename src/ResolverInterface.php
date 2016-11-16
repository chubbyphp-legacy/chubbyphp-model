<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

interface ResolverInterface
{
    /**
     * @param string $modelClass
     * @param string $id
     *
     * @return \Closure
     */
    public function find(string $modelClass, string $id): \Closure;

    /**
     * @param string $modelClass
     * @param array  $criteria
     *
     * @return \Closure
     */
    public function findOneBy(string $modelClass, array $criteria): \Closure;

    /**
     * @param string     $modelClass
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return \Closure
     */
    public function findBy(
        string $modelClass,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null
    ): \Closure;

    /**
     * @param string $modelClass
     *
     * @return RepositoryInterface
     */
    public function getRepositoryByClass(string $modelClass): RepositoryInterface;
}
