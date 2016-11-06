<?php

namespace Chubbyphp\Tests\Model\Cache;

use Chubbyphp\Model\Cache\ModelCache;
use Chubbyphp\Model\Cache\ModelNotFoundException;
use Chubbyphp\Tests\Model\Resources\User;
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

        $cache->set(new User($id));
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
        $cache->set(new User($id));

        self::assertTrue($cache->has($id));
    }

    public function testGetWithoutValue()
    {
        $id = (string) Uuid::uuid4();

        self::expectException(ModelNotFoundException::class);
        self::expectExceptionMessage(sprintf('Model with id %s not found within cache', $id));

        $cache = new ModelCache();
        $cache->get($id);
    }

    public function testGetWithValue()
    {
        $id = (string) Uuid::uuid4();

        $user = new User($id);

        $cache = new ModelCache();
        $cache->set($user);

        self::assertSame($user, $cache->get($id));
    }

    public function testRemoveValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new ModelCache();
        $cache->remove($id);
    }
}
