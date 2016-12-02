<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Cache;

interface EntryCacheInterface
{
    /**
     * @param string $id
     * @param array  $entry
     *
     * @return EntryCacheInterface
     */
    public function set(string $id, array $entry): EntryCacheInterface;

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
     * @return EntryCacheInterface
     */
    public function remove(string $id): EntryCacheInterface;

    /**
     * @return EntryCacheInterface
     */
    public function clear(): EntryCacheInterface;
}
