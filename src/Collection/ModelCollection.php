<?php

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

class ModelCollection implements ModelCollectionInterface
{
    /**
     * @var ModelInterface[]|array
     */
    private $initialModels;

    /**
     * @var ModelInterface[]|array
     */
    private $models;

    /**
     * @var ModelInterface[]|array
     */
    private $toRemoveModels;

    /**
     * @param ModelInterface[]|array $models
     */
    public function __construct(array $models = [])
    {
        $this->initialModels = $models;
        $this->models = $models;
        $this->toRemoveModels = [];
    }

    /**
     * @return ModelInterface
     */
    public function current()
    {
        return current($this->models);
    }

    /**
     * @return ModelInterface|false
     */
    public function next()
    {
        return next($this->models);
    }

    /**
     * @return string
     */
    public function key()
    {
        return key($this->models);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return (bool) current($this->models);
    }

    public function rewind()
    {
        reset($this->models);
    }

    /**
     * @param ModelInterface $model
     *
     * @return ModelCollectionInterface
     */
    public function add(ModelInterface $model): ModelCollectionInterface
    {
        $this->models[$model->getId()] = $model;

        if (isset($this->toRemoveModels[$model->getId()])) {
            unset($this->toRemoveModels[$model->getId()]);
        }

        return $this;
    }

    /**
     * @param ModelInterface $model
     *
     * @return ModelCollectionInterface
     */
    public function remove(ModelInterface $model): ModelCollectionInterface
    {
        if (isset($this->models[$model->getId()])) {
            unset($this->models[$model->getId()]);
        }

        if (isset($this->initialModels[$model->getId()])) {
            $this->toRemoveModels[$model->getId()] = $model;
        }

        return $this;
    }

    /**
     * @return ModelInterface[]|array
     */
    public function toPersist(): array
    {
        return $this->models;
    }

    /**
     * @return ModelInterface[]|array
     */
    public function toRemove(): array
    {
        return $this->toRemoveModels;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $serialzedModels = [];
        foreach ($this->models as $model) {
            $serialzedModels[] = $model->jsonSerialize();
        }

        return $serialzedModels;
    }
}
