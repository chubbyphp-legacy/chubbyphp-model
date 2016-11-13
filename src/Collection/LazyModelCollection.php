<?php

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

class LazyModelCollection implements ModelCollectionInterface
{
    /**
     * @var \Closure
     */
    private $resolver;

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
     * ResolverCollection constructor.
     * @param \Closure $resolver
     */
    public function __construct(\Closure $resolver)
    {
        $this->resolver = $resolver;
    }

    private function loadModels()
    {
        if (null !== $this->models) {
            return;
        }

        $resolver = $this->resolver;
        $this->initialModels = (array) $resolver();
        $this->models = $this->initialModels;
        $this->toRemoveModels = [];
    }

    /**
     * @return ModelInterface
     */
    public function current()
    {
        $this->loadModels();

        return current($this->models);
    }

    /**
     * @return ModelInterface|false
     */
    public function next()
    {
        $this->loadModels();

        return next($this->models);
    }

    /**
     * @return string
     */
    public function key()
    {
        $this->loadModels();

        return key($this->models);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        $this->loadModels();

        return (bool) current($this->models);
    }

    public function rewind()
    {
        $this->loadModels();

        reset($this->models);
    }

    /**
     * @param ModelInterface $model
     * @return ModelCollectionInterface
     */
    public function add(ModelInterface $model): ModelCollectionInterface
    {
        $this->loadModels();

        $this->models[$model->getId()] = $model;

        return $this;
    }

    /**
     * @param ModelInterface $model
     * @return ModelCollectionInterface
     */
    public function remove(ModelInterface $model): ModelCollectionInterface
    {
        $this->loadModels();

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
        $this->loadModels();

        return $this->models;
    }

    /**
     * @return ModelInterface[]|array
     */
    public function toRemove(): array
    {
        $this->loadModels();

        return $this->toRemoveModels;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $this->loadModels();

        $serialzedModels = [];
        foreach ($this->models as $model) {
            $serialzedModels[] = $model->jsonSerialize();
        }

        return $serialzedModels;
    }
}
