<?php

namespace Chubbyphp\Tests\Model\Collection;

use Chubbyphp\Model\Collection\LazyModelCollection;
use Chubbyphp\Tests\Model\Resources\User;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Chubbyphp\Model\Collection\LazyModelCollection
 */
final class LazyModelCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testAddModel()
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new LazyModelCollection(function () {
            return [];
        });
        $modelCollection->addModel($user);

        self::assertCount(1, $modelCollection->getModels());
    }

    public function testRemoveModel()
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new LazyModelCollection(function () use ($user) {
            return [$user];
        });

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

        $modelCollection = new LazyModelCollection(function () {
            return [];
        });
        $modelCollection->setModels([$user]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    public function testIteratable()
    {
        $id = (string) Uuid::uuid4();

        $user = new User($id);
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new LazyModelCollection(function () use ($user) {
            return [$user];
        });

        foreach ($modelCollection as $key => $model) {
            self::assertSame($id, $key);
            self::assertSame($user, $model);

            return;
        }

        self::fail('collection is not iteratable');
    }

    public function testJsonSerialize()
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new LazyModelCollection(function () use ($user) {
            return [$user];
        });

        $modelsAsArray = json_decode(json_encode($modelCollection), true);

        self::assertCount(1, $modelsAsArray);

        self::assertSame('username1', $modelsAsArray[0]['username']);
        self::assertTrue($modelsAsArray[0]['active']);
    }
}
