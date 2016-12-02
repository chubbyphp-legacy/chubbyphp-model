<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Cache;

final class EntryCache implements EntryCacheInterface
{
    /**
     * @var array
     */
    private $cache = [];

    /**
     * @param string $id
     * @param array  $entry
     *
     * @return EntryCacheInterface
     */
    public function set(string $id, array $entry): EntryCacheInterface
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
     * @return EntryCacheInterface
     */
    public function remove(string $id): EntryCacheInterface
    {
        unset($this->cache[$id]);

        return $this;
    }

    /**
     * @return EntryCacheInterface
     */
    public function clear(): EntryCacheInterface
    {
        $this->cache = [];

        return $this;
    }
}
