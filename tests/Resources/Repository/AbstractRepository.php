<?php

declare(strict_types=1);

namespace MyProject\Repository;

use Chubbyphp\Model\Collection\ModelCollectionInterface;
use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\ModelSortTrait;
use Chubbyphp\Model\Reference\ModelReferenceInterface;
use Chubbyphp\Model\RelatedModelManipulationStack;
use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Model\ResolverInterface;
use Chubbyphp\Model\Sorter\ModelSorter;

abstract class AbstractRepository implements RepositoryInterface
{
    use ModelSortTrait;

    /**
     * @var ModelInterface[]|array
     */
    protected $modelEntries;

    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * @param array $modelEntries
     * @param ResolverInterface $resolver
     */
    public function __construct(array $modelEntries, ResolverInterface $resolver)
    {
        $this->modelEntries = [];
        foreach ($modelEntries as $modelEntry) {
            $this->modelEntries[$modelEntry['id']] = $modelEntry;
        }

        $this->resolver = $resolver;
    }

    /**
     * @param string|null $id
     *
     * @return ModelInterface|null
     */
    public function find(string $id = null)
    {
        if (null === $id) {
            return null;
        }

        if (!isset($this->modelEntries[$id])) {
            return null;
        }

        return $this->fromPersistence($this->modelEntries[$id]);
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return ModelInterface|null
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $models = $this->findBy($criteria, $orderBy, 1, 0);

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
     * @return ModelInterface[]|array
     */
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        if ([] === $this->modelEntries) {
            return [];
        }

        $models = [];
        foreach ($this->modelEntries as $id => $modelEntry) {
            foreach ($criteria as $key => $value) {
                if ($modelEntry[$key] !== $value) {
                    continue 2;
                }
            }

            $models[] = $this->fromPersistence($modelEntry);
        }

        if ([] === $models) {
            return [];
        }

        $model = reset($models);
        $modelClass = get_class($model);

        $models = $this->sort($modelClass, $models, $orderBy);

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
     * @return RepositoryInterface
     */
    public function persist(ModelInterface $model): RepositoryInterface
    {
        $id = $model->getId();
        $modelEntry = $model->toPersistence();

        if (!$alreadyExists = (bool) $this->find($id)) {
            $alreadyExists = $this->callbackIfReference($id, $modelEntry, function (string $id, array $modelEntry) {
                $this->insert($id, $modelEntry);
            });
        }

        $stack = new RelatedModelManipulationStack();

        foreach ($modelEntry as $field => $value) {
            if ($value instanceof ModelCollectionInterface) {
                $stack->addToRemoveModels($value->getInitialModels());
                $stack->addToPersistModels($value->getModels());
                unset($modelEntry[$field]);
            } else if ($value instanceof ModelReferenceInterface) {
                $modelEntry[$field.'Id'] = $this->persistModelReference($value, $stack);

                unset($modelEntry[$field]);
            }
        }

        if (!$alreadyExists) {
            $this->insert($id, $modelEntry);
        } else {
            $this->update($id, $modelEntry);
        }

        $this->persistRelatedModels($stack->getToPersistModels());
        $this->removeRelatedModels($stack->getToRemoveModels());

        return $this;
    }

    /**
     * @param ModelInterface $model
     *
     * @return RepositoryInterface
     */
    public function remove(ModelInterface $model): RepositoryInterface
    {
        $id = $model->getId();

        if (null === $this->find($id)) {
            return $this;
        }

        $modelEntry = $model->toPersistence();

        $this->callbackIfReference($id, $modelEntry, function (string $id, array $modelEntry) {
            $this->update($id, $modelEntry);
        });

        foreach ($modelEntry as $field => $value) {
            if ($value instanceof ModelCollectionInterface) {
                $this->removeRelatedModels($value->getInitialModels());
            } else if ($value instanceof ModelReferenceInterface) {
                if (null !== $initialModel = $value->getInitialModel()) {
                    $this->removeRelatedModel($initialModel);
                }
            }
        }

        unset($this->modelEntries[$id]);

        return $this;
    }

    /**
     * @return RepositoryInterface
     */
    public function clear(): RepositoryInterface
    {
        return $this;
    }

    /**
     * @param string $id
     * @param array  $modelEntry
     */
    protected function insert(string $id, array $modelEntry)
    {
        $this->modelEntries[$id] = $modelEntry;
    }

    /**
     * @param string $id
     * @param array  $modelEntry
     */
    protected function update(string $id, array $modelEntry)
    {
        $this->modelEntries[$id] = $modelEntry;
    }

    /**
     * @param string $id
     * @param array $modelEntry
     * @return bool
     */
    private function callbackIfReference(string $id, array $modelEntry, \Closure $callback): bool
    {
        $gotReference = false;
        foreach ($modelEntry as $field => $value) {
            if ($value instanceof ModelCollectionInterface) {
                unset($modelEntry[$field]);
            } else if ($value instanceof ModelReferenceInterface) {
                $modelEntry[$field.'Id'] = null;
                $gotReference = true;
                unset($modelEntry[$field]);
            }
        }

        if ($gotReference) {
            $callback($id, $modelEntry);

            return true;
        }

        return false;
    }

    /**
     * @param ModelReferenceInterface $reference
     * @param RelatedModelManipulationStack $stack
     * @return null|string
     */
    private function persistModelReference(ModelReferenceInterface $reference, RelatedModelManipulationStack $stack)
    {
        $initialModel = $reference->getInitialModel();
        $model = $reference->getModel();

        if (null !== $initialModel && (null === $model || $model->getId() !== $initialModel->getId())) {
            $stack->addToRemoveModel($initialModel);
        }

        if (null !== $model) {
            $this->persistRelatedModel($model);

            return $model->getId();
        }

        return null;
    }

    /**
     * @param ModelInterface[]|array $toRemoveModels
     */
    private function persistRelatedModels(array $toRemoveModels)
    {
        foreach ($toRemoveModels as $toRemoveRelatedModel) {
            $this->persistRelatedModel($toRemoveRelatedModel);
        }
    }

    /**
     * @param ModelInterface $model
     */
    private function persistRelatedModel(ModelInterface $model)
    {
        $this->resolver->persist($model);
    }

    /**
     * @param ModelInterface[]|array $toRemoveModels
     */
    private function removeRelatedModels(array $toRemoveModels)
    {
        foreach ($toRemoveModels as $toRemoveRelatedModel) {
            $this->removeRelatedModel($toRemoveRelatedModel);
        }
    }

    /**
     * @param ModelInterface $model
     */
    private function removeRelatedModel(ModelInterface $model)
    {
        $this->resolver->remove($model);
    }

    /**
     * @param array $modelEntry
     *
     * @return ModelInterface
     */
    abstract protected function fromPersistence(array $modelEntry): ModelInterface;
}
