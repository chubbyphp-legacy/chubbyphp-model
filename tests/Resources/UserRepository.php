<?php

namespace Chubbyphp\Tests\Model\Resources;

use Chubbyphp\Model\Exception\NotUniqueException;
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
     * @param array $criteria
     *
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
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

        if (null !== $orderBy) {
            usort($models, function (array $a, array $b) use ($orderBy) {
                foreach ($orderBy as $key => $value) {
                    $sorting = strcmp($a[$key], $b[$key]);
                    if (0 !== $sorting) {
                        return $sorting;
                    }
                }

                return 0;
            });
        }

        if (null !== $limit && null !== $offset) {
            return array_slice($models, $offset, $limit);
        }

        if (null !== $limit) {
            return array_slice($models, 0, $limit);
        }

        return $models;
    }

    /**
     * @param ModelInterface $model
     *
     * @throws \Exception
     */
    public function persist(ModelInterface $model)
    {
        $this->modelRows[$model->getId()] = $model->toRow();
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
            return;
        }

        unset($this->modelRows[$id]);
    }
}
