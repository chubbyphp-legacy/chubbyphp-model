<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Model\MissingRepositoryException;
use Chubbyphp\Tests\Model\Resources\User;

final class MissingRepositoryExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Chubbyphp\Model\MissingRepositoryException::create
     */
    public function testCreate()
    {
        $exception = MissingRepositoryException::create(User::class);

        self::assertInstanceOf(MissingRepositoryException::class, $exception);
        self::assertSame(sprintf('Missing repository for model "%s"', User::class), $exception->getMessage());
    }
}
