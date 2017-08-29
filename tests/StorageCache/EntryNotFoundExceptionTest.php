<?php

namespace Chubbyphp\Tests\Model\StorageCache;

use Chubbyphp\Model\StorageCache\EntryNotFoundException;

final class EntryNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Chubbyphp\Model\StorageCache\EntryNotFoundException::fromId
     */
    public function testCreate()
    {
        $id = uniqid('id');

        $exception = EntryNotFoundException::fromId($id);

        self::assertSame(
            sprintf('Entry with id %s not found within storage cache', $id),
            $exception->getMessage()
        );
    }
}
