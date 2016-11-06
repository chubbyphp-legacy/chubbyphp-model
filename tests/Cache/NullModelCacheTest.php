<?php

namespace Chubbyphp\Tests\Model\Cache;

use Chubbyphp\Model\Cache\NullModelCache;
use Chubbyphp\Model\Cache\ModelNotFoundException;
use Chubbyphp\Tests\Model\Resources\User;
use Ramsey\Uuid\Uuid;

/**
 * @covers Chubbyphp\Model\Cache\NullModelCache
 */
final class NullModelCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testSetValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new NullModelCache();

        $cache->set(new User($id));
    }

    public function testHasValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new NullModelCache();

        self::assertFalse($cache->has($id));
    }

    public function testGetValue()
    {
        $id = (string) Uuid::uuid4();

        self::expectException(ModelNotFoundException::class);
        self::expectExceptionMessage(sprintf('Model with id %s not found within cache', $id));

        $cache = new NullModelCache();
        $cache->get($id);
    }

    public function testRemoveValue()
    {
        $id = (string) Uuid::uuid4();

        $cache = new NullModelCache();
        $cache->remove($id);
    }
}
