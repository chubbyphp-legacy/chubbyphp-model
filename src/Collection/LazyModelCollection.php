<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

final class LazyModelCollection implements ModelCollectionInterface
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

    private function resolveModels()
    {
        if (null === $this->resolver) {
            return;
        }

        $resolver = $this->resolver;

        $this->resolver = null;

        $this->setModels((array) $resolver());
        $this->initialModels = $this->models;
    }

    /**
     * @param ModelInterface $model
     * @return ModelCollectionInterface
     */
    public function addModel(ModelInterface $model): ModelCollectionInterface
    {
        $this->resolveModels();

        $this->models[$model->getId()] = $model;

        return $this;
    }

    /**
     * @param ModelInterface $model
     * @return ModelCollectionInterface
     */
    public function removeModel(ModelInterface $model): ModelCollectionInterface
    {
        $this->resolveModels();

        if (isset($this->models[$model->getId()])) {
            unset($this->models[$model->getId()]);
        }

        return $this;
    }

    /**
     * @param ModelInterface[]|array $models
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

        return $this->models;
    }

    /**
     * @return ModelInterface[]|array
     */
    public function getInitialModels(): array
    {
        $this->resolveModels();

        return $this->initialModels;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $this->resolveModels();

        $serializedModels = [];
        foreach ($this->models as $model) {
            $serializedModels[] = $model->jsonSerialize();
        }

        return $serializedModels;
    }
}
