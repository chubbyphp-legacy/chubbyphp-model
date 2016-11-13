<?php

namespace Chubbyphp\Model;

use Chubbyphp\Model\Collection\LazyPersistedModelCollection;

final class Resolver implements ResolverInterface
{
    /**
     * @param RepositoryInterface $repository
     * @param string $id
     * @return \Closure
     */
    public function findResolver(RepositoryInterface $repository, string $id): \Closure
    {
        return function () use ($repository, $id) {
            return $repository->find($id);
        };
    }

    /**
     * @param RepositoryInterface $repository
     * @param array $criteria
     * @return \Closure
     */
    public function findOneByResolver(RepositoryInterface $repository, array $criteria): \Closure
    {
        return function () use ($repository, $criteria) {
            return $repository->findOneBy($criteria);
        };
    }

    /**
     * @param RepositoryInterface $repository
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return LazyPersistedModelCollection
     */
    public function findByCollection(
        RepositoryInterface $repository,
        array $criteria, array
        $orderBy = null,
        int $limit = null,
        int $offset = null
    ): LazyPersistedModelCollection {
        return new LazyPersistedModelCollection(function () use ($repository, $criteria, $orderBy, $limit, $offset) {
            return $repository->findBy($criteria, $orderBy, $limit, $offset);
        });
    }
}
