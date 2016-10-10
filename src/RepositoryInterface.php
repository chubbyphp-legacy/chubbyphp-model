<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

use Chubbyphp\Model\Exception\AlreadyKnownException;
use Chubbyphp\Model\Exception\NotUniqueException;
use Chubbyphp\Model\Exception\UnknownException;

interface RepositoryInterface
{
    /**
     * @return string
     */
    public function getModelClass(): string;

    /**
     * @param string $id
     *
     * @return ModelInterface|null
     */
    public function find(string $id);

    /**
     * @param array $criteria
     *
     * @return ModelInterface[]array
     */
    public function findBy(array $criteria = []): array;

    /**
     * @param array $criteria
     *
     * @return ModelInterface|null
     *
     * @throws NotUniqueException
     */
    public function findOneBy(array $criteria = []);

    /**
     * @param ModelInterface $model
     *
     * @throws AlreadyKnownException
     */
    public function insert(ModelInterface $model);

    /**
     * @param ModelInterface $model
     *
     * @throws UnknownException
     */
    public function update(ModelInterface $model);

    /**
     * @param ModelInterface $model
     *
     * @throws UnknownException
     */
    public function delete(ModelInterface $model);
}
