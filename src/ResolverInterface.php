<?php

namespace Chubbyphp\Model;

use Chubbyphp\Model\Collection\ModelCollection;

interface ResolverInterface
{
    /**
     * @param RepositoryInterface $repository
     * @param string $id
     * @return \Closure
     */
    public function findResolver(RepositoryInterface $repository, string $id): \Closure;

    /**
     * @param RepositoryInterface $repository
     * @param array $criteria
     * @return \Closure
     */
    public function findOneByResolver(RepositoryInterface $repository, array $criteria): \Closure;

    /**
     * @param RepositoryInterface $repository
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return ModelCollection
     */
    public function findByCollection(
        RepositoryInterface $repository,
        array $criteria, array
        $orderBy = null,
        int $limit = null,
        int $offset = null
    ): ModelCollection;
}
