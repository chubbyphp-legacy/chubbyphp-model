<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Cache;

use Chubbyphp\Model\ModelInterface;

final class ModelCache implements ModelCacheInterface
{
    /**
     * @var ModelInterface[]|array
     */
    private $cache = [];

    /**
     * @param ModelInterface $model
     *
     * @return ModelCacheInterface
     */
    public function set(ModelInterface $model): ModelCacheInterface
    {
        $this->cache[$model->getId()] = $model;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->cache);
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
        if (!$this->has($id)) {
            throw ModelNotFoundException::fromId($id);
        }

        return $this->cache[$id];
    }

    /**
     * @param string $id
     *
     * @return ModelCacheInterface
     */
    public function remove(string $id): ModelCacheInterface
    {
        unset($this->cache[$id]);

        return $this;
    }
}
