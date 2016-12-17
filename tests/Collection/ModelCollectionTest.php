<?php

namespace Chubbyphp\Tests\Model\Collection;

use Chubbyphp\Model\Collection\ModelCollection;
use MyProject\Model\MyModel;

final class ModelCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::addModel
     */
    public function testAddModel()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new ModelCollection();

        $modelCollection->addModel($model);

        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::removeModel
     */
    public function testRemoveModel()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new ModelCollection([$model]);

        self::assertCount(1, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());

        $modelCollection->removeModel($model);

        self::assertCount(1, $modelCollection->getInitialModels());
        self::assertCount(0, $modelCollection->getModels());

        $modelCollection->removeModel($model);
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::setModels
     */
    public function testSetModels()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new ModelCollection();

        $modelCollection->setModels([$model]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::getInitialModels
     */
    public function testGetInitialModels()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new ModelCollection();

        $modelCollection->setModels([$model]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::getModels
     */
    public function testGetModels()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new ModelCollection();

        $modelCollection->setModels([$model]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::getIterator
     */
    public function testIteratable()
    {
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new ModelCollection([$model]);

        foreach ($modelCollection as $key => $model) {
            self::assertSame('id1', $key);
            self::assertSame($model, $model);

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
        $model = MyModel::create('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelCollection = new ModelCollection([$model]);

        $modelsAsArray = json_decode(json_encode($modelCollection), true);

        self::assertCount(1, $modelsAsArray);

        self::assertSame('name1', $modelsAsArray[0]['name']);
        self::assertSame('category', $modelsAsArray[0]['category']);
    }
}
