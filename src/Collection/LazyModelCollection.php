<?php

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

class LazyModelCollection implements ModelCollectionInterface
{
    /**
     * @var \Closure;
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
     * @param \Closure $resolver
     */
    public function __construct(\Closure $resolver)
    {
        $this->resolver = $resolver;
    }

    private function loadModels()
    {
        if (null !== $this->initialModels) {
            return;
        }

        $resolver = $this->resolver;

        $models = $this->modelsWithIdKey((array) $resolver());

        $this->initialModels = $models;
        $this->models = $models;
    }

    /**
     * @param ModelInterface[]|array $models
     *
     * @return ModelInterface[]|array
     */
    private function modelsWithIdKey(array $models): array
    {
        $modelsWithIdKey = [];
        foreach ($models as $model) {
            if (!$model instanceof ModelInterface) {
                throw new \InvalidArgumentException(
                    sprintf('Model with index %d needs to implement: %s', ModelInterface::class)
                );
            }

            $modelsWithIdKey[$model->getId()] = $model;
        }

        return $modelsWithIdKey;
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
     * @param ModelInterface[]|array $models
     */
    public function set(array $models)
    {
        $this->loadModels();

        $this->models = $this->modelsWithIdKey($models);
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

        $toRemoveModels = [];
        foreach ($this->initialModels as $initialModel) {
            if (!isset($this->models[$initialModel->getId()])) {
                $toRemoveModels[$initialModel->getId()] = $initialModel;
            }
        }

        return $toRemoveModels;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $this->loadModels();

        $serializedModels = [];
        foreach ($this->models as $model) {
            $serializedModels[] = $model->jsonSerialize();
        }

        return $serializedModels;
    }
}
