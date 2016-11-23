<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Model\MissingRepositoryException;
use Chubbyphp\Tests\Model\Resources\User;

/**
 * @covers Chubbyphp\Model\MissingRepositoryException
 */
final class MissingRepositoryExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $exception = MissingRepositoryException::create(User::class);

        self::assertInstanceOf(MissingRepositoryException::class, $exception);
        self::assertSame(sprintf('Missing repository for model "%s"', User::class), $exception->getMessage());
    }
}
