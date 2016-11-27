<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

final class ModelCollection implements ModelCollectionInterface
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
     * @param ModelInterface[]|array $models
     */
    public function __construct(array $models = [])
    {
        $this->setModels($models);
        $this->initialModels = $this->models;
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
        return $this->models;
    }

    /**
     * @return ModelInterface[]|array
     */
    public function getInitialModels(): array
    {
        return $this->initialModels;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $serializedModels = [];
        foreach ($this->models as $model) {
            $serializedModels[] = $model->jsonSerialize();
        }

        return $serializedModels;
    }
}
