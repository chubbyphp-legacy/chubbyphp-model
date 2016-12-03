<?php

namespace Chubbyphp\Tests\Model\StorageCache;

use Chubbyphp\Model\StorageCache\EntryNotFoundException;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Chubbyphp\Model\StorageCache\EntryNotFoundException
 */
final class EntryNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $id = (string) Uuid::uuid4();

        $exception = EntryNotFoundException::fromId($id);

        self::assertSame(
            sprintf('Entry with id %s not found within cache', $id),
            $exception->getMessage()
        );
    }
}
