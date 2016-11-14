<?php

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\RepositoryInterface;

class ModelCollection implements ModelCollectionInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var ModelInterface[]|array
     */
    private $initialModels;

    /**
     * @var ModelInterface[]|array
     */
    private $models;

    /**
     * @param RepositoryInterface $repository
     * @param array               $models
     */
    public function __construct(RepositoryInterface $repository, array $models = [])
    {
        $this->repository = $repository;
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
     * @param ModelInterface[]|array $models
     */
    public function set(array $models)
    {
        $this->models = $this->modelsWithIdKey($models);
    }

    public function persist()
    {
        foreach ($this->models as $model) {
            $this->repository->persist($model);
        }
    }

    public function remove()
    {
        foreach ($this->initialModels as $initialModel) {
            if (!isset($this->models[$initialModel->getId()])) {
                $this->repository->remove($initialModel);
            }
        }
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
