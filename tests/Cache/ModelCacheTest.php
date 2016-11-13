<?php

namespace Chubbyphp\Tests\Model\Cache;

use Chubbyphp\Model\Cache\ModelCache;
use Chubbyphp\Model\Cache\RowNotFoundException;
use Ramsey\Uuid\Uuid;

/**
 * @covers Chubbyphp\Model\Cache\ModelCache
 */
final class ModelCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testSetValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new ModelCache();

        $cache->set($id, []);
    }

    public function testHasWithoutValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new ModelCache();

        self::assertFalse($cache->has($id));
    }

    public function testHasWithValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new ModelCache();
        $cache->set($id, []);

        self::assertTrue($cache->has($id));
    }

    public function testGetWithoutValue()
    {
        $id = (string) Uuid::uuid4();

        self::expectException(RowNotFoundException::class);
        self::expectExceptionMessage(sprintf('Row with id %s not found within cache', $id));

        $cache = new ModelCache();
        $cache->get($id);
    }

    public function testGetWithValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new ModelCache();
        $cache->set($id, ['key' => 'value']);

        self::assertSame(['key' => 'value'], $cache->get($id));
    }

    public function testRemoveValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new ModelCache();
        $cache->remove($id);
    }
}
