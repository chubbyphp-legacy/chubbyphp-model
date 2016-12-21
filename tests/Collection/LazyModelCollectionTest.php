<?php

namespace Chubbyphp\Tests\Model\Collection;

use Chubbyphp\Model\Collection\LazyModelCollection;
use MyProject\Model\MyModel;

final class LazyModelCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::addModel
     */
    public function testAddModel()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new LazyModelCollection(function () {
            return [];
        });

        $modelCollection->addModel($model);

        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::removeModel
     */
    public function testRemoveModel()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new LazyModelCollection(function () use ($model) {
            return [$model];
        });

        self::assertCount(1, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());

        $modelCollection->removeModel($model);

        self::assertCount(1, $modelCollection->getInitialModels());
        self::assertCount(0, $modelCollection->getModels());

        $modelCollection->removeModel($model);
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::setModels
     */
    public function testSetModels()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new LazyModelCollection(function () {
            return [];
        });

        $modelCollection->setModels([$model]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::getInitialModels
     */
    public function testGetInitialModels()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new LazyModelCollection(function () {
            return [];
        });

        $modelCollection->setModels([$model]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::getModels
     */
    public function testGetModels()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new LazyModelCollection(function () {
            return [];
        });

        $modelCollection->setModels([$model]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::getIterator
     */
    public function testIteratable()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new LazyModelCollection(function () use ($model) {
            return [$model];
        });

        foreach ($modelCollection as $model) {
            self::assertSame($model, $model);

            return;
        }

        self::fail('collection is not iteratable');
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::jsonSerialize
     */
    public function testJsonSerialize()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new LazyModelCollection(function () use ($model) {
            return [$model];
        });

        $modelsAsArray = json_decode(json_encode($modelCollection), true);

        self::assertCount(1, $modelsAsArray);

        self::assertSame('name1', $modelsAsArray[0]['name']);
        self::assertSame('category', $modelsAsArray[0]['category']);
    }
}
