<?php

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

interface ModelCollectionInterface extends \Iterator, \JsonSerializable
{
    /**
     * @param ModelInterface[] $models
     */
    public function set(array $models);

    public function persist();

    public function remove();
}
