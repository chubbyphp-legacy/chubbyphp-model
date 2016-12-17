<?php

declare(strict_types=1);

namespace MyProject\Repository;

use Chubbyphp\Model\Collection\ModelCollectionInterface;
use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\Reference\ModelReferenceInterface;
use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Model\ResolverInterface;

abstract class AbstractRepository implements RepositoryInterface
{
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

        if (null !== $orderBy) {
            usort($models, function (ModelInterface $a, ModelInterface $b) use ($orderBy) {
                foreach ($orderBy as $key => $value) {
                    $reflectionProperty = new \ReflectionProperty(get_class($a), $key);
                    $reflectionProperty->setAccessible(true);
                    $sorting = strcmp($reflectionProperty->getValue($a), $reflectionProperty->getValue($b));
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
     * @return RepositoryInterface
     */
    public function persist(ModelInterface $model): RepositoryInterface
    {
        $id = $model->getId();
        $modelEntry = $model->toPersistence();

        $modelCollections = [];
        foreach ($modelEntry as $field => $value) {
            if ($value instanceof ModelCollectionInterface) {
                $modelCollections[] = $value;
                unset($modelEntry[$field]);
            } elseif ($value instanceof ModelReferenceInterface) {
                $modelEntry[$field.'Id'] = $this->persistReference($value);
                unset($modelEntry[$field]);
            }
        }

        $this->modelEntries[$id] = $modelEntry;

        foreach ($modelCollections as $modelCollection) {
            $this->persistCollection($modelCollection);
        }

        return $this;
    }

    /**
     * @param ModelInterface $model
     *
     * @return RepositoryInterface
     */
    public function remove(ModelInterface $model): RepositoryInterface
    {
        $modelEntry = $model->toPersistence();

        foreach ($modelEntry as $field => $value) {
            if ($value instanceof ModelCollectionInterface) {
                $this->removeRelatedModels($value);
            } elseif ($value instanceof ModelReferenceInterface) {
                if (null !== $initialModel = $value->getInitialModel()) {
                    $this->removeRelatedModel($initialModel);
                }
            }
        }

        unset($this->modelEntries[$model->getId()]);

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
     * @param ModelReferenceInterface $reference
     * @return null|string
     */
    private function persistReference(ModelReferenceInterface $reference)
    {
        $initialModel = $reference->getInitialModel();
        $model = $reference->getModel();

        if (null !== $initialModel && (null === $model || $model->getId() !== $initialModel->getId())) {
            $this->removeRelatedModel($initialModel);
        }

        if (null !== $model) {
            $this->persistRelatedModel($model);

            return $model->getId();
        }

        return null;
    }

    /**
     * @param ModelCollectionInterface $modelCollection
     */
    private function persistCollection(ModelCollectionInterface $modelCollection)
    {
        $initialModels = $modelCollection->getInitialModels();
        $models = $modelCollection->getModels();

        foreach ($models as $model) {
            $this->persistRelatedModel($model);
            if (isset($initialModels[$model->getId()])) {
                unset($initialModels[$model->getId()]);
            }
        }

        foreach ($initialModels as $initialModel) {
            $this->removeRelatedModel($initialModel);
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
     * @param ModelCollectionInterface $modelCollection
     */
    private function removeRelatedModels(ModelCollectionInterface $modelCollection)
    {
        foreach ($modelCollection->getInitialModels() as $initialModel) {
            $this->removeRelatedModel($initialModel);
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
