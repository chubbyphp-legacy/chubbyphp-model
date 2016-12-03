<?php

declare(strict_types=1);

namespace Chubbyphp\Model\StorageCache;

final class ArrayStorageCache implements StorageCacheInterface
{
    /**
     * @var array
     */
    private $cache = [];

    /**
     * @param array $cache
     */
    public function __construct(array $cache = [])
    {
        $this->cache = $cache;
    }

    /**
     * @param string $id
     * @param array  $entry
     *
     * @return StorageCacheInterface
     */
    public function set(string $id, array $entry): StorageCacheInterface
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
     * @return StorageCacheInterface
     */
    public function remove(string $id): StorageCacheInterface
    {
        unset($this->cache[$id]);

        return $this;
    }

    /**
     * @return StorageCacheInterface
     */
    public function clear(): StorageCacheInterface
    {
        $this->cache = [];

        return $this;
    }
}
