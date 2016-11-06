<?php

namespace Chubbyphp\Tests\Model\Cache;

use Chubbyphp\Model\Cache\ModelNotFoundException;
use Ramsey\Uuid\Uuid;

/**
 * @covers Chubbyphp\Model\Cache\ModelNotFoundException
 */
final class ModelNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $id = (string) Uuid::uuid4();

        $exception = ModelNotFoundException::fromId($id);

        self::assertSame(
            sprintf('Model with id %s not found within cache', $id),
            $exception->getMessage()
        );
    }
}
