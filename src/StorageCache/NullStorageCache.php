<?php

declare(strict_types=1);

namespace Chubbyphp\Model\StorageCache;

final class NullStorageCache implements StorageCacheInterface
{
    /**
     * @param string $id
     * @param array  $entry
     *
     * @return StorageCacheInterface
     */
    public function set(string $id, array $entry): StorageCacheInterface
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
     * @return array
     *
     * @throws EntryNotFoundException
     */
    public function get(string $id)
    {
        throw EntryNotFoundException::fromId($id);
    }

    /**
     * @param string $id
     *
     * @return StorageCacheInterface
     */
    public function remove(string $id): StorageCacheInterface
    {
        return $this;
    }

    /**
     * @return StorageCacheInterface
     */
    public function clear(): StorageCacheInterface
    {
        return $this;
    }
}
