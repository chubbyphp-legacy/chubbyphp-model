<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\ResolverInterface;
use Chubbyphp\Model\ModelSortTrait;

final class LazyModelCollection implements ModelCollectionInterface
{
    use ModelSortTrait;

    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var string
     */
    private $foreignField;

    /**
     * @var string
     */
    private $foreignId;

    /**
     * @var array|null
     */
    private $orderBy;

    /**
     * @var bool
     */
    private $resolved = false;

    /**
     * @var ModelInterface[]|array
     */
    private $initialModels;

    /**
     * @var ModelInterface[]|array
     */
    private $models;

    /**
     * @var \ReflectionProperty
     */
    private $propertyReflection;

    /**
     * @param ResolverInterface $resolver
     * @param string $modelClass
     * @param string $foreignField
     * @param string $foreignId
     * @param array|null $orderBy
     */
    public function __construct(
        ResolverInterface $resolver,
        string $modelClass,
        string $foreignField,
        string $foreignId,
        array $orderBy = null
    ) {
        $this->resolver = $resolver;
        $this->modelClass = $modelClass;
        $this->foreignField = $foreignField;
        $this->foreignId = $foreignId;
        $this->orderBy = $orderBy;

        $this->propertyReflection = new \ReflectionProperty($this->modelClass, $this->foreignField);
        $this->propertyReflection->setAccessible(true);
    }

    private function resolveModels()
    {
        if ($this->resolved) {
            return;
        }

        $this->resolved = true;

        $criteria = [$this->foreignField => $this->foreignId];

        $models = [];
        foreach ($this->resolver->findBy($this->modelClass, $criteria, $this->orderBy) as $model) {
            $models[$model->getId()] = $model;
        }

        $this->initialModels = $models;
        $this->models = $models;
    }

    /**
     * @param ModelInterface $model
     *
     * @return ModelCollectionInterface
     */
    public function addModel(ModelInterface $model): ModelCollectionInterface
    {
        $this->resolveModels();

        $this->propertyReflection->setValue($model, $this->foreignId);

        $this->models[$model->getId()] = $model;

        return $this;
    }

    /**
     * @param ModelInterface $model
     *
     * @return ModelCollectionInterface
     */
    public function removeModel(ModelInterface $model): ModelCollectionInterface
    {
        $this->resolveModels();

        if (isset($this->models[$model->getId()])) {
            $this->propertyReflection->setValue($model, null);

            unset($this->models[$model->getId()]);
        }

        return $this;
    }

    /**
     * @param ModelInterface[]|array $models
     *
     * @return ModelCollectionInterface
     */
    public function setModels(array $models): ModelCollectionInterface
    {
        $this->resolveModels();

        $this->models = [];
        foreach ($models as $model) {
            $this->addModel($model);
        }

        return $this;
    }

    /**
     * @return ModelInterface[]|array
     */
    public function getModels(): array
    {
        $this->resolveModels();

        return $this->sort($this->modelClass, array_values($this->models), $this->orderBy);
    }

    /**
     * @return ModelInterface[]|array
     */
    public function getInitialModels(): array
    {
        $this->resolveModels();

        return array_values($this->initialModels);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $this->resolveModels();

        return new \ArrayIterator($this->getModels());
    }

    /**
     * @return int
     */
    public function count()
    {
        $this->resolveModels();

        return count($this->getModels());
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $this->resolveModels();

        $serializedModels = [];
        foreach ($this->getModels() as $model) {
            $serializedModels[] = $model->jsonSerialize();
        }

        return $serializedModels;
    }

    /**
     * @return string
     */
    public function getForeignField(): string
    {
        return $this->foreignField;
    }

    /**
     * @return string
     */
    public function getForeignId(): string
    {
        return $this->foreignId;
    }
}
