<?php

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

class ModelCollection implements ModelCollectionInterface
{
    /**
     * @var ModelInterface[]|array
     */
    private $models = [];

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
     * @return ModelCollectionInterface
     */
    public function add(ModelInterface $model): ModelCollectionInterface
    {
        $this->models[$model->getId()] = $model;

        return $this;
    }

    /**
     * @param ModelInterface $model
     * @return ModelCollectionInterface
     */
    public function remove(ModelInterface $model): ModelCollectionInterface
    {
        if (isset($this->models[$model->getId()])) {
            unset($this->models[$model->getId()]);
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
        return [];
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
