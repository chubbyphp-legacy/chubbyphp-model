<?php

namespace Chubbyphp\Model;

final class Resolver implements ResolverInterface
{
    /**
     * @param RepositoryInterface $repository
     * @param string              $id
     *
     * @return \Closure
     */
    public function find(RepositoryInterface $repository, string $id): \Closure
    {
        return function () use ($repository, $id) {
            return $repository->find($id);
        };
    }

    /**
     * @param RepositoryInterface $repository
     * @param array               $criteria
     *
     * @return \Closure
     */
    public function findOneBy(RepositoryInterface $repository, array $criteria): \Closure
    {
        return function () use ($repository, $criteria) {
            return $repository->findOneBy($criteria);
        };
    }
}
