<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\ModelSortTrait;

final class ModelCollection implements ModelCollectionInterface
{
    use ModelSortTrait;

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
     * @var ModelInterface[]|array
     */
    private $models;

    /**
     * @param string $modelClass
     * @param string $foreignField
     * @param string $foreignId
     * @param array|null $orderBy
     */
    public function __construct(
        string $modelClass,
        string $foreignField,
        string $foreignId,
        array $orderBy = null
    ) {
        $this->modelClass = $modelClass;
        $this->foreignField = $foreignField;
        $this->foreignId = $foreignId;
        $this->orderBy = $orderBy;

        $this->models = [];
    }

    /**
     * @param ModelInterface $model
     *
     * @return ModelCollectionInterface
     */
    public function addModel(ModelInterface $model): ModelCollectionInterface
    {
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
        if (isset($this->models[$model->getId()])) {
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
        return $this->sort($this->modelClass, array_values($this->models), $this->orderBy);
    }

    /**
     * @return ModelInterface[]|array
     */
    public function getInitialModels(): array
    {
        return [];
    }


    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getModels());
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
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
