<?php

namespace Chubbyphp\Tests\Model\Exception;

use Chubbyphp\Model\Exception\NotUniqueException;
use Chubbyphp\Tests\Model\Resources\User;

class NotUniqueExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $exception = NotUniqueException::create(User::class, ['email' => 'firstname.lastname@domain.tld'], 3);

        self::assertSame(
            'There are 3 models of class '.User::class.' for criteria email: firstname.lastname@domain.tld',
            $exception->getMessage()
        );
    }
}
