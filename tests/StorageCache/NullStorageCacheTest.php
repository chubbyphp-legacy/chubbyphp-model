<?php

namespace Chubbyphp\Tests\Model\StorageCache;

use Chubbyphp\Model\StorageCache\NullStorageCache;
use Chubbyphp\Model\StorageCache\EntryNotFoundException;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Chubbyphp\Model\StorageCache\NullStorageCache
 */
final class NullStorageCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testSetValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new NullStorageCache();

        $cache->set($id, []);
    }

    public function testHasWithoutValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new NullStorageCache();

        self::assertFalse($cache->has($id));
    }

    public function testHasWithValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new NullStorageCache();
        $cache->set($id, []);

        self::assertFalse($cache->has($id));
    }

    public function testGetWithoutValue()
    {
        self::expectException(EntryNotFoundException::class);

        $id = (string) Uuid::uuid4();

        $cache = new NullStorageCache();
        $cache->get($id);
    }

    public function testGetWithValue()
    {
        self::expectException(EntryNotFoundException::class);

        $id = (string) Uuid::uuid4();

        $cache = new NullStorageCache();
        $cache->set($id, ['key' => 'value']);

        $cache->get($id);
    }

    public function testRemoveValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new NullStorageCache();
        $cache->remove($id);
    }

    public function testClear()
    {
        $id1 = (string) Uuid::uuid4();
        $id2 = (string) Uuid::uuid4();

        $cache = new NullStorageCache();

        $cache->set($id1, []);
        $cache->set($id2, []);

        self::assertFalse($cache->has($id1));
        self::assertFalse($cache->has($id2));

        $cache->clear();

        self::assertFalse($cache->has($id1));
        self::assertFalse($cache->has($id2));
    }
}
