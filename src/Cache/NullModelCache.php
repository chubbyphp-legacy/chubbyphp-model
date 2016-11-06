<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Cache;

use Chubbyphp\Model\ModelInterface;

final class NullModelCache implements ModelCacheInterface
{
    /**
     * @param ModelInterface $model
     *
     * @return ModelCacheInterface
     */
    public function set(ModelInterface $model): ModelCacheInterface
    {
        return $this;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return false;
    }

    /**
     * @param string $id
     *
     * @return ModelInterface
     *
     * @throws ModelNotFoundException
     */
    public function get(string $id)
    {
        throw ModelNotFoundException::fromId($id);
    }

    /**
     * @param string $id
     *
     * @return ModelCacheInterface
     */
    public function remove(string $id): ModelCacheInterface
    {
        return $this;
    }
}
