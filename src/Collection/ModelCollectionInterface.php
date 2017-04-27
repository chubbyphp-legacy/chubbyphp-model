<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

interface ModelCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param ModelInterface $model
     *
     * @return ModelCollectionInterface
     */
    public function addModel(ModelInterface $model): ModelCollectionInterface;

    /**
     * @param ModelInterface $model
     *
     * @return ModelCollectionInterface
     */
    public function removeModel(ModelInterface $model): ModelCollectionInterface;

    /**
     * @param ModelInterface[]|array $models
     *
     * @return ModelCollectionInterface
     */
    public function setModels(array $models): ModelCollectionInterface;

    /**
     * @return ModelInterface[]|array
     */
    public function getModels(): array;

    /**
     * @return ModelInterface[]|array
     */
    public function getInitialModels(): array;

    /**
     * @return string
     */
    public function getForeignField(): string;

    /**
     * @return string
     */
    public function getForeignId(): string;
}
