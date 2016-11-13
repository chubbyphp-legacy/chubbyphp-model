<?php

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

interface ModelCollectionInterface extends \Iterator, \JsonSerializable
{
    /**
     * @param ModelInterface $model
     * @return ModelCollectionInterface
     */
    public function add(ModelInterface $model): ModelCollectionInterface;

    /**
     * @param ModelInterface $model
     * @return ModelCollectionInterface
     */
    public function remove(ModelInterface $model): ModelCollectionInterface;

    /**
     * @return ModelInterface[]|array
     */
    public function toPersist(): array;

    /**
     * @return ModelInterface[]|array
     */
    public function toRemove(): array;
}
