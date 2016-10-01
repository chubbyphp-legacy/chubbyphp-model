<?php

namespace Chubbyphp\Tests\Model\Exception;

use Chubbyphp\Model\Exception\AlreadyKnownException;
use Chubbyphp\Tests\Model\Resources\User;
use Ramsey\Uuid\Uuid;

class AlreadyKnownExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $id = (string) Uuid::uuid4();

        $exception = AlreadyKnownException::create(User::class, $id);

        self::assertSame('Already known model of class '.User::class.' with id '.$id, $exception->getMessage());
    }
}
