<?php

namespace Chubbyphp\Tests\Model\Collection;

use Chubbyphp\Model\Collection\ModelCollection;
use Chubbyphp\Tests\Model\Resources\User;
use Ramsey\Uuid\Uuid;

final class ModelCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::addModel
     */
    public function testAddModel()
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new ModelCollection([]);
        $modelCollection->addModel($user);

        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::removeModel
     */
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

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::setModels
     */
    public function testSetModels()
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new ModelCollection([]);
        $modelCollection->setModels([$user]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::getInitialModels
     */
    public function testGetInitialModels()
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new ModelCollection([]);
        $modelCollection->setModels([$user]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::getModels
     */
    public function testGetModels()
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new ModelCollection([]);
        $modelCollection->setModels([$user]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::getIterator
     */
    public function testIteratable()
    {
        $id = (string) Uuid::uuid4();

        $user = new User($id);
        $user->setUsername('username1');
        $user->setPassword('password');
        $user->setActive(true);

        $modelCollection = new ModelCollection([$user]);

        foreach ($modelCollection as $key => $model) {
            self::assertSame($id, $key);
            self::assertSame($user, $model);

            return;
        }

        self::fail('collection is not iteratable');
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::jsonSerialize
     */
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
