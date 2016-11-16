<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Cache;

final class ModelCache implements ModelCacheInterface
{
    /**
     * @var array
     */
    private $cache = [];

    /**
     * @param string $id
     * @param array  $entry
     *
     * @return ModelCacheInterface
     */
    public function set(string $id, array $entry): ModelCacheInterface
    {
        $this->cache[$id] = $entry;

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
     * @return array
     *
     * @throws EntryNotFoundException
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw EntryNotFoundException::fromId($id);
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
