<?php

namespace Chubbyphp\Tests\Model\Collection;

use Chubbyphp\Model\Collection\ModelCollection;
use MyProject\Model\MyEmbeddedModel;

final class ModelCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::addModel
     */
    public function testAddModel()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelCollection = new ModelCollection(MyEmbeddedModel::class, 'modelId', 'id1', ['name' => 'ASC']);

        $modelCollection->addModel($model);

        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::removeModel
     */
    public function testRemoveModel()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelCollection = new ModelCollection(MyEmbeddedModel::class, 'modelId', 'id1', ['name' => 'ASC']);
        $modelCollection->addModel($model);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());

        $modelCollection->removeModel($model);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(0, $modelCollection->getModels());

        $modelCollection->removeModel($model);
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::setModels
     */
    public function testSetModels()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelCollection = new ModelCollection(MyEmbeddedModel::class, 'modelId', 'id1', ['name' => 'ASC']);

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
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelCollection = new ModelCollection(MyEmbeddedModel::class, 'modelId', 'id1', ['name' => 'ASC']);

        $modelCollection->setModels([$model]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::getModels
     * @covers \Chubbyphp\Model\ModelSortTrait::sort
     */
    public function testGetModels()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelCollection = new ModelCollection(MyEmbeddedModel::class, 'modelId', 'id1', ['name' => 'ASC']);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(0, $modelCollection->getModels());

        $modelCollection->setModels([$model]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::getModels
     * @covers \Chubbyphp\Model\ModelSortTrait::sort
     */
    public function testGetModelsWithoutOrderBy()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelCollection = new ModelCollection(MyEmbeddedModel::class, 'modelId', 'id1');

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(0, $modelCollection->getModels());

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
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelCollection = new ModelCollection(MyEmbeddedModel::class, 'modelId', 'id1', ['name' => 'ASC']);
        $modelCollection->addModel($model);

        foreach ($modelCollection as $model) {
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
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelCollection = new ModelCollection(MyEmbeddedModel::class, 'modelId', 'id1', ['name' => 'ASC']);
        $modelCollection->addModel($model);

        $modelsAsArray = json_decode(json_encode($modelCollection), true);

        self::assertCount(1, $modelsAsArray);

        self::assertSame('name1', $modelsAsArray[0]['name']);
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::getForeignField()
     */
    public function testGetCriteria()
    {
        $modelCollection = new ModelCollection(MyEmbeddedModel::class, 'modelId', 'id1', ['name' => 'ASC']);

        self::assertSame('modelId', $modelCollection->getForeignField());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\ModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\ModelCollection::getForeignId()
     */
    public function testGetOrderBy()
    {
        $modelCollection = new ModelCollection(MyEmbeddedModel::class, 'modelId', 'id1', ['name' => 'ASC']);

        self::assertSame('id1', $modelCollection->getForeignId());
    }
}
