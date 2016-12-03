<?php

namespace Chubbyphp\Tests\Model\StorageCache;

use Chubbyphp\Model\StorageCache\ArrayStorageCache;
use Chubbyphp\Model\StorageCache\EntryNotFoundException;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Chubbyphp\Model\StorageCache\ArrayStorageCache
 */
final class ArrayStorageCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testSetValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new ArrayStorageCache();

        $cache->set($id, []);
    }

    public function testHasWithoutValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new ArrayStorageCache();

        self::assertFalse($cache->has($id));
    }

    public function testHasWithValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new ArrayStorageCache();
        $cache->set($id, []);

        self::assertTrue($cache->has($id));
    }

    public function testGetWithoutValue()
    {
        $id = (string) Uuid::uuid4();

        self::expectException(EntryNotFoundException::class);

        $cache = new ArrayStorageCache();
        $cache->get($id);
    }

    public function testGetWithValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new ArrayStorageCache();
        $cache->set($id, ['key' => 'value']);

        self::assertSame(['key' => 'value'], $cache->get($id));
    }

    public function testRemoveValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new ArrayStorageCache();
        $cache->remove($id);
    }

    public function testClear()
    {
        $id1 = (string) Uuid::uuid4();
        $id2 = (string) Uuid::uuid4();

        $cache = new ArrayStorageCache();

        $cache->set($id1, []);
        $cache->set($id2, []);

        self::assertTrue($cache->has($id1));
        self::assertTrue($cache->has($id2));

        $cache->clear();

        self::assertFalse($cache->has($id1));
        self::assertFalse($cache->has($id2));
    }
}
