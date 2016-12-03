<?php

declare(strict_types=1);

namespace Chubbyphp\Model\StorageCache;

interface StorageCacheInterface
{
    /**
     * @param string $id
     * @param array  $entry
     *
     * @return StorageCacheInterface
     */
    public function set(string $id, array $entry): StorageCacheInterface;

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool;

    /**
     * @param string $id
     *
     * @return array
     *
     * @throws EntryNotFoundException
     */
    public function get(string $id);

    /**
     * @param string $id
     *
     * @return StorageCacheInterface
     */
    public function remove(string $id): StorageCacheInterface;

    /**
     * @return StorageCacheInterface
     */
    public function clear(): StorageCacheInterface;
}
