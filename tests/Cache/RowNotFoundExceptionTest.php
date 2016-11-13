<?php

namespace Chubbyphp\Tests\Model\Cache;

use Chubbyphp\Model\Cache\RowNotFoundException;
use Ramsey\Uuid\Uuid;

/**
 * @covers Chubbyphp\Model\Cache\RowNotFoundException
 */
final class RowNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $id = (string) Uuid::uuid4();

        $exception = RowNotFoundException::fromId($id);

        self::assertSame(
            sprintf('Row with id %s not found within cache', $id),
            $exception->getMessage()
        );
    }
}
