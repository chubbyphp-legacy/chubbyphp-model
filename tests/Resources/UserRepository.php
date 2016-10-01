<?php

namespace Chubbyphp\Tests\Model\Resources;

use Chubbyphp\Model\Exception\AlreadyKnownException;
use Chubbyphp\Model\Exception\NotUniqueException;
use Chubbyphp\Model\Exception\UnknownException;
use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\RepositoryInterface;

final class UserRepository implements RepositoryInterface
{
    /**
     * @var array[]
     */
    private $modelRows;

    /**
     * @param array $modelRows
     */
    public function __construct(array $modelRows = [])
    {
        $this->modelRows = [];
        foreach ($modelRows as $modelRow) {
            $this->modelRows[$modelRow['id']] = $modelRow;
        }
    }

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return User::class;
    }

    /**
     * @param string $id
     *
     * @return ModelInterface|null
     */
    public function find(string $id)
    {
        if (!isset($this->modelRows[$id])) {
            return null;
        }

        /** @var User $modelClass */
        $modelClass = $this->getModelClass();

        return $modelClass::fromRow($this->modelRows[$id]);
    }

    /**
     * @param array $criteria
     *
     * @return array
     */
    public function findBy(array $criteria = []): array
    {
        /** @var User $modelClass */
        $modelClass = $this->getModelClass();

        $models = [];
        foreach ($this->modelRows as $modelRow) {
            foreach ($criteria as $key => $value) {
                if ($modelRow[$key] !== $value) {
                    continue 2;
                }
            }

            $models[] = $modelClass::fromRow($modelRow);
        }

        return $models;
    }

    /**
     * @param array $criteria
     *
     * @return ModelInterface|null
     */
    public function findOneBy(array $criteria = [])
    {
        $models = $this->findBy($criteria);

        if ([] === $models) {
            return null;
        }

        $modelsCount = count($models);

        if (1 === $modelsCount) {
            return reset($models);
        }

        throw NotUniqueException::create($this->getModelClass(), $criteria, $modelsCount);
    }

    /**
     * @param ModelInterface $model
     *
     * @throws \Exception
     */
    public function insert(ModelInterface $model)
    {
        $id = $model->getId();
        if (isset($this->modelRows[$id])) {
            throw AlreadyKnownException::create($this->getModelClass(), $id);
        }

        $this->modelRows[$model->getId()] = $model->toRow();
    }

    /**
     * @param ModelInterface $model
     *
     * @throws \Exception
     */
    public function update(ModelInterface $model)
    {
        $id = $model->getId();
        if (!isset($this->modelRows[$id])) {
            throw UnknownException::create($this->getModelClass(), $id);
        }

        $this->modelRows[$id] = $model->toRow();
    }

    /**
     * @param ModelInterface $model
     *
     * @throws \Exception
     */
    public function delete(ModelInterface $model)
    {
        $id = $model->getId();
        if (!isset($this->modelRows[$id])) {
            throw UnknownException::create($this->getModelClass(), $id);
        }

        unset($this->modelRows[$id]);
    }
}
