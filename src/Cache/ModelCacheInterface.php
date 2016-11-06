<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Cache;

use Chubbyphp\Model\ModelInterface;

interface ModelCacheInterface
{
    /**
     * @param ModelInterface $model
     *
     * @return ModelCacheInterface
     */
    public function set(ModelInterface $model): ModelCacheInterface;

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool;

    /**
     * @param string $id
     *
     * @return ModelInterface
     *
     * @throws ModelNotFoundException
     */
    public function get(string $id);

    /**
     * @param string $id
     *
     * @return ModelCacheInterface
     */
    public function remove(string $id): ModelCacheInterface;
}
