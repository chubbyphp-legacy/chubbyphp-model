<?php

namespace Chubbyphp\Tests\Model\Exception;

use Chubbyphp\Model\Exception\UnknownException;
use Chubbyphp\Tests\Model\Resources\User;
use Ramsey\Uuid\Uuid;

/**
 * @covers Chubbyphp\Model\Exception\UnknownException
 */
class UnknownExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $id = (string) Uuid::uuid4();

        $exception = UnknownException::create(User::class, $id);

        self::assertSame('Unknown model of class '.User::class.' with id '.$id, $exception->getMessage());
    }
}
