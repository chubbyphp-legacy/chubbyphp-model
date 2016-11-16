<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

interface ModelCollectionInterface extends \JsonSerializable
{
    /**
     * @param ModelInterface[]|array $models
     */
    public function set(array $models);

    /**
     * @return ModelInterface[]|array
     */
    public function get(): array;

    /**
     * @return ModelInterface[]|array
     */
    public function getInitial(): array;
}
