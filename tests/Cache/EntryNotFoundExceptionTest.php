<?php

namespace Chubbyphp\Tests\Model\Cache;

use Chubbyphp\Model\Cache\EntryNotFoundException;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Chubbyphp\Model\Cache\EntryNotFoundException
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
