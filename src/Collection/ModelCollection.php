<?php

declare(strict_types=1);

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
     * @param ModelInterface[]|array $models
     */
    public function __construct(array $models = [])
    {
        $models = $this->modelsWithIdKey($models);

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
     * @param ModelInterface[]|array $models
     */
    public function set(array $models)
    {
        $this->models = $this->modelsWithIdKey($models);
    }

    /**
     * @return ModelInterface[]|array
     */
    public function get(): array
    {
        return $this->models;
    }

    /**
     * @return ModelInterface[]|array
     */
    public function getInitial(): array
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
