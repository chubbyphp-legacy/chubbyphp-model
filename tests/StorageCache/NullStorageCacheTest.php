<?php

namespace Chubbyphp\Tests\Model\StorageCache;

use Chubbyphp\Model\StorageCache\NullStorageCache;
use Chubbyphp\Model\StorageCache\EntryNotFoundException;

final class NullStorageCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Chubbyphp\Model\StorageCache\NullStorageCache::set
     */
    public function testSetValue()
    {
        $id = uniqid('id');;

        $cache = new NullStorageCache();

        $cache->set($id, []);
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\NullStorageCache::has
     */
    public function testHasWithoutValue()
    {
        $id = uniqid('id');;

        $cache = new NullStorageCache();

        self::assertFalse($cache->has($id));
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\NullStorageCache::has
     */
    public function testHasWithValue()
    {
        $id = uniqid('id');;

        $cache = new NullStorageCache();
        $cache->set($id, []);

        self::assertFalse($cache->has($id));
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\NullStorageCache::get
     */
    public function testGetWithoutValue()
    {
        self::expectException(EntryNotFoundException::class);

        $id = uniqid('id');;

        $cache = new NullStorageCache();
        $cache->get($id);
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\NullStorageCache::get
     */
    public function testGetWithValue()
    {
        self::expectException(EntryNotFoundException::class);

        $id = uniqid('id');;

        $cache = new NullStorageCache();
        $cache->set($id, ['key' => 'value']);

        $cache->get($id);
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\NullStorageCache::remove
     */
    public function testRemoveValue()
    {
        $id = uniqid('id');;

        $cache = new NullStorageCache();
        $cache->remove($id);
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\NullStorageCache::clear
     */
    public function testClear()
    {
        $id1 = uniqid('id');;
        $id2 = uniqid('id');;

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
