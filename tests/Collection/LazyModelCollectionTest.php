<?php

namespace Chubbyphp\Tests\Model\Collection;

use Chubbyphp\Model\Collection\LazyModelCollection;
use Chubbyphp\Model\ResolverInterface;
use MyProject\Model\MyEmbeddedModel;
use MyProject\Model\MyEmbeddedModelNoJsonSerialize;

final class LazyModelCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::addModel
     */
    public function testAddModel()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'ASC'];
        $return = [];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

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
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'ASC'];
        $return = [$model];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

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
        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'ASC'];
        $return = [];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelCollection->setModels([$model]);

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(1, $modelCollection->getModels());

        $model = MyEmbeddedModel::create('id2');
        $model->setName('name2');

        $modelCollection->setModels([$model]);

        self::assertCount(1, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::getInitialModels
     */
    public function testGetInitialModels()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'ASC'];
        $return = [$model];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

        $modelCollection->setModels([]);

        self::assertCount(1, $modelCollection->getInitialModels());
        self::assertCount(0, $modelCollection->getModels());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::getModels
     * @covers \Chubbyphp\Model\ModelSortTrait::sort
     */
    public function testGetModelsSortingAsc()
    {
        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'ASC'];
        $return = [];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(0, $modelCollection->getModels());

        $model1 = MyEmbeddedModel::create('id1');
        $model1->setName('name1');

        $model2 = MyEmbeddedModel::create('id2');
        $model2->setName('name2');

        $model3 = MyEmbeddedModel::create('id3');
        $model3->setName('name1');

        $modelCollection->setModels([$model1, $model2, $model3]);

        $models = $modelCollection->getModels();

        self::assertCount(3, $models);

        self::assertSame('id1', $models[0]->getId());
        self::assertSame('id3', $models[1]->getId());
        self::assertSame('id2', $models[2]->getId());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::getModels
     * @covers \Chubbyphp\Model\ModelSortTrait::sort
     */
    public function testGetModelsSortingDesc()
    {
        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'DESC'];
        $return = [];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(0, $modelCollection->getModels());

        $model1 = MyEmbeddedModel::create('id1');
        $model1->setName('name1');

        $model2 = MyEmbeddedModel::create('id2');
        $model2->setName('name2');

        $model3 = MyEmbeddedModel::create('id3');
        $model3->setName('name1');

        $modelCollection->setModels([$model1, $model2, $model3]);

        $models = $modelCollection->getModels();

        self::assertCount(3, $models);

        self::assertSame('id2', $models[0]->getId());
        self::assertSame('id1', $models[1]->getId());
        self::assertSame('id3', $models[2]->getId());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::getModels
     * @covers \Chubbyphp\Model\ModelSortTrait::sort
     */
    public function testGetModelsWithoutOrderBy()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $return = [];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], null, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            null
        );

        self::assertCount(0, $modelCollection->getInitialModels());
        self::assertCount(0, $modelCollection->getModels());

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
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'ASC'];
        $return = [$model];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

        foreach ($modelCollection as $model) {
            self::assertSame($model, $model);

            return;
        }

        self::fail('collection is not iteratable');
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::count
     */
    public function testCountable()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'ASC'];
        $return = [$model];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

        self::assertCount(1, $modelCollection);
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::jsonSerialize
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::jsonSerializableOrException
     */
    public function testJsonSerialize()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'ASC'];
        $return = [$model];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

        $modelsAsArray = json_decode(json_encode($modelCollection), true);

        self::assertCount(1, $modelsAsArray);

        self::assertSame('name1', $modelsAsArray[0]['name']);
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::jsonSerialize
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::jsonSerializableOrException
     */
    public function testJsonSerializeWithModelsNotimplementingJsonSerialize()
    {
        self::expectException(\LogicException::class);
        self::expectExceptionMessage('does not implement JsonSerializable');

        $model = MyEmbeddedModelNoJsonSerialize::create('id1');
        $model->setName('name1');

        $modelClass = MyEmbeddedModelNoJsonSerialize::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'ASC'];
        $return = [$model];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

        json_encode($modelCollection);
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::getForeignField
     */
    public function testGetForeignField()
    {
        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'ASC'];
        $return = [];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

        self::assertSame('modelId', $modelCollection->getForeignField());
    }

    /**
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::__construct
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::resolveModels
     * @covers \Chubbyphp\Model\Collection\LazyModelCollection::getForeignId
     */
    public function testGetForeignId()
    {
        $modelClass = MyEmbeddedModel::class;
        $foreignField = 'modelId';
        $foreignId = 'id1';
        $orderBy = ['name' => 'ASC'];
        $return = [];

        $modelCollection = new LazyModelCollection(
            $this->getResolver($modelClass, [$foreignField => $foreignId], $orderBy, $return),
            $modelClass,
            $foreignField,
            $foreignId,
            $orderBy
        );

        self::assertSame('id1', $modelCollection->getForeignId());
    }

    /**
     * @param string $expectedModelClass
     * @param array  $expectedCriteria
     * @param array  $expectedOrderBy
     * @param array  $return
     *
     * @return ResolverInterface
     */
    private function getResolver(
        string $expectedModelClass,
        array $expectedCriteria,
        array $expectedOrderBy = null,
        array $return
    ): ResolverInterface {
        /** @var ResolverInterface|\PHPUnit_Framework_MockObject_MockObject $resolver */
        $resolver = $this->getMockBuilder(ResolverInterface::class)
            ->setMethods(['findBy'])
            ->getMockForAbstractClass();

        $resolver->expects(self::any())
            ->method('findBy')
            ->willReturnCallback(
                function (
                    string $modelClass,
                    array $criteria,
                    array $orderBy = null
                ) use (
                    $expectedModelClass,
                    $expectedCriteria,
                    $expectedOrderBy,
                    $return
                ) {
                    self::assertSame($expectedModelClass, $modelClass);
                    self::assertSame($expectedCriteria, $criteria);
                    self::assertSame($expectedOrderBy, $orderBy);

                    return $return;
                }
            );

        return $resolver;
    }
}
