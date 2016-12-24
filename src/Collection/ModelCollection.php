<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

final class ModelCollection implements ModelCollectionInterface
{
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
        return $this->sort(array_values($this->models));
    }

    /**
     * @return ModelInterface[]|array
     */
    public function getInitialModels(): array
    {
        return [];
    }

    /**
     * @param array $models
     * @return array
     */
    private function sort(array $models): array
    {
        if ([] === $models) {
            return [];
        }

        if (null === $this->orderBy) {
            return $models;
        }

        $reflections = [];
        foreach ($this->orderBy as $property => $sortingDirection) {
            $reflection = new \ReflectionProperty($this->modelClass, $property);
            $reflection->setAccessible(true);

            $reflections[$property] = $reflection;
        }

        usort($models, function (ModelInterface $a, ModelInterface $b) use ($reflections) {
            foreach ($this->orderBy as $property => $sortingDirection) {
                $reflection = $reflections[$property];
                $sorting = strcmp($reflection->getValue($a), $reflection->getValue($b));
                if ($sortingDirection === 'DESC') {
                    $sorting = $sorting * -1;
                }

                if (0 !== $sorting) {
                    return $sorting;
                }
            }

            return 0;
        });

        return $models;
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
