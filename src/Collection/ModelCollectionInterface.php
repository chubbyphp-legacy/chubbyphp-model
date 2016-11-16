<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Collection;

use Chubbyphp\Model\ModelInterface;

interface ModelCollectionInterface extends \Iterator, \JsonSerializable
{
    /**
     * @param ModelInterface[]|array $models
     */
    public function setModels(array $models);

    /**
     * @return ModelInterface[]|array
     */
    public function getModels(): array;

    /**
     * @return ModelInterface[]|array
     */
    public function getInitialModels(): array;
}
