<?php

namespace Chubbyphp\Tests\Model\Collection;

use Chubbyphp\Model\Collection\ModelCollection;
use Chubbyphp\Tests\Model\Resources\User;

/**
 * @covers Chubbyphp\Model\Collection\ModelCollection
 */
final class ModelCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testAddModel()
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new ModelCollection();
        $modelCollection->addModel($user);

        self::assertCount(1, $modelCollection->getModels());
    }

    public function testRemoveModel()
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new ModelCollection([$user]);

        self::assertCount(1, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());

        $modelCollection->removeModel($user);

        self::assertCount(1, $modelCollection->getInitialModels());
        self::assertCount(0, $modelCollection->getModels());

        $modelCollection->removeModel($user);
    }

    public function testSetModels()
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new ModelCollection();
        $modelCollection->setModels([$user]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    public function testJsonSerialize()
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new ModelCollection([$user]);

        $modelsAsArray = json_decode(json_encode($modelCollection), true);

        self::assertCount(1, $modelsAsArray);

        self::assertSame('username1', $modelsAsArray[0]['username']);
        self::assertTrue($modelsAsArray[0]['active']);
    }
}
