<?php

namespace Chubbyphp\Tests\Model\Resources;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\RepositoryInterface;

final class UserRepository implements RepositoryInterface
{
    /**
     * @var array[]
     */
    private $modelEntries;

    /**
     * @param array $modelEntries
     */
    public function __construct(array $modelEntries = [])
    {
        $this->modelEntries = [];
        foreach ($modelEntries as $modelEntry) {
            $this->modelEntries[$modelEntry['id']] = $modelEntry;
        }
    }

    /**
     * @param string $modelClass
     *
     * @return bool
     */
    public function isResponsible(string $modelClass): bool
    {
        return $modelClass === User::class;
    }

    /**
     * @param string $id
     *
     * @return ModelInterface|null
     */
    public function find(string $id)
    {
        if (!isset($this->modelEntries[$id])) {
            return null;
        }

        return User::fromPersistence($this->modelEntries[$id]);
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

        return reset($models);
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        $models = [];
        foreach ($this->modelEntries as $modelEntry) {
            foreach ($criteria as $key => $value) {
                if ($modelEntry[$key] !== $value) {
                    continue 2;
                }
            }

            $models[] = User::fromPersistence($modelEntry);
        }

        if (null !== $orderBy) {
            usort($models, function (ModelInterface $a, ModelInterface $b) use ($orderBy) {
                foreach ($orderBy as $key => $value) {
                    $propertyReflection = new \ReflectionProperty(get_class($a), $key);
                    $propertyReflection->setAccessible(true);
                    $sorting = strcmp($propertyReflection->getValue($a), $propertyReflection->getValue($b));
                    if ($value === 'DESC') {
                        $sorting = $sorting * -1;
                    }

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
     * @thentries \Exception
     */
    public function persist(ModelInterface $model)
    {
        $this->modelEntries[$model->getId()] = $model->toPersistence();
    }

    /**
     * @param ModelInterface $model
     *
     * @thentries \Exception
     */
    public function remove(ModelInterface $model)
    {
        $id = $model->getId();
        if (!isset($this->modelEntries[$id])) {
            return;
        }

        unset($this->modelEntries[$id]);
    }
}
