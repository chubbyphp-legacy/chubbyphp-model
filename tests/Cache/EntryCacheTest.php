<?php

namespace Chubbyphp\Tests\Model\Cache;

use Chubbyphp\Model\Cache\EntryCache;
use Chubbyphp\Model\Cache\EntryNotFoundException;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Chubbyphp\Model\Cache\EntryCache
 */
final class EntryCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testSetValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new EntryCache();

        $cache->set($id, []);
    }

    public function testHasWithoutValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new EntryCache();

        self::assertFalse($cache->has($id));
    }

    public function testHasWithValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new EntryCache();
        $cache->set($id, []);

        self::assertTrue($cache->has($id));
    }

    public function testGetWithoutValue()
    {
        $id = (string) Uuid::uuid4();

        self::expectException(EntryNotFoundException::class);
        self::expectExceptionMessage(sprintf('Entry with id %s not found within cache', $id));

        $cache = new EntryCache();
        $cache->get($id);
    }

    public function testGetWithValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new EntryCache();
        $cache->set($id, ['key' => 'value']);

        self::assertSame(['key' => 'value'], $cache->get($id));
    }

    public function testRemoveValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new EntryCache();
        $cache->remove($id);
    }

    public function testClear()
    {
        $id1 = (string) Uuid::uuid4();
        $id2 = (string) Uuid::uuid4();

        $cache = new EntryCache();

        $cache->set($id1, []);
        $cache->set($id2, []);

        self::assertTrue($cache->has($id1));
        self::assertTrue($cache->has($id2));

        $cache->clear();

        self::assertFalse($cache->has($id1));
        self::assertFalse($cache->has($id2));
    }
}
