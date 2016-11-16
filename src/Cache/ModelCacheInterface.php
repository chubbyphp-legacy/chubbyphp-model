<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Cache;

interface ModelCacheInterface
{
    /**
     * @param string $id
     * @param array  $row
     *
     * @return ModelCacheInterface
     */
    public function set(string $id, array $row): ModelCacheInterface;

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
     * @return ModelCacheInterface
     */
    public function remove(string $id): ModelCacheInterface;
}
