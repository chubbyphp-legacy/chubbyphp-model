<?php

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\RepositoryInterface;

class LazyModelCollection implements ModelCollectionInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

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
     * LazyModelCollection constructor.
     *
     * @param RepositoryInterface $repository
     * @param array               $criteria
     * @param array|null          $orderBy
     * @param int|null            $limit
     * @param int|null            $offset
     */
    public function __construct(
        RepositoryInterface $repository,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null
    ) {
        $this->repository = $repository;
        $this->resolver = function () use ($repository, $criteria, $orderBy, $limit, $offset) {
            return $repository->findBy($criteria, $orderBy, $limit, $offset);
        };
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
     * @return ModelInterface[]
     */
    public function toPersist(): array
    {
        $this->loadModels();

        return $this->models;
    }

    /**
     * @return array
     */
    public function toRemove(): array
    {
        $this->loadModels();

        $toRemove = [];
        foreach ($this->initialModels as $initialModel) {
            if (!isset($this->models[$initialModel->getId()])) {
                $toRemove[$initialModel->getId()] = $initialModel;
            }
        }

        return $toRemove;
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
