<?php

namespace Chubbyphp\Tests\Model\StorageCache;

use Chubbyphp\Model\StorageCache\ArrayStorageCache;
use Chubbyphp\Model\StorageCache\EntryNotFoundException;

final class ArrayStorageCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::__construct
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::set
     */
    public function testSetValue()
    {
        $id = uniqid('id');;

        $cache = new ArrayStorageCache();

        $cache->set($id, []);
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::__construct
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::has
     */
    public function testHasWithoutValue()
    {
        $id = uniqid('id');;

        $cache = new ArrayStorageCache();

        self::assertFalse($cache->has($id));
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::__construct
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::has
     */
    public function testHasWithValue()
    {
        $id = uniqid('id');;

        $cache = new ArrayStorageCache();
        $cache->set($id, []);

        self::assertTrue($cache->has($id));
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::__construct
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::get
     */
    public function testGetWithoutValue()
    {
        self::expectException(EntryNotFoundException::class);

        $id = uniqid('id');;

        $cache = new ArrayStorageCache();
        $cache->get($id);
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::__construct
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::get
     */
    public function testGetWithValue()
    {
        $id = uniqid('id');;

        $cache = new ArrayStorageCache([$id => ['key' => 'value']]);

        self::assertSame(['key' => 'value'], $cache->get($id));
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::__construct
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::remove
     */
    public function testRemoveValue()
    {
        $id = uniqid('id');;

        $cache = new ArrayStorageCache([$id => ['key' => 'value']]);
        $cache->remove($id);
    }

    /**
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::__construct
     * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache::clear
     */
    public function testClear()
    {
        $id1 = uniqid('id');;
        $id2 = uniqid('id');;

        $cache = new ArrayStorageCache([$id1 => [], $id2 => []]);

        self::assertTrue($cache->has($id1));
        self::assertTrue($cache->has($id2));

        $cache->clear();

        self::assertFalse($cache->has($id1));
        self::assertFalse($cache->has($id2));
    }
}
