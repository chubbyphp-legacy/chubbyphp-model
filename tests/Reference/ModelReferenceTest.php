<?php

namespace Chubbyphp\Tests\Model\Reference;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\Reference\ModelReference;
use MyProject\Model\MyEmbeddedModel;

final class ModelReferenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::setModel
     */
    public function testSetModel()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelReference = new ModelReference();

        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertInstanceOf(ModelInterface::class, $modelReference->getModel());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::getInitialModel
     */
    public function testGetInitialModel()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelReference = new ModelReference();

        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertInstanceOf(ModelInterface::class, $modelReference->getModel());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::getModel
     */
    public function testGetModel()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelReference = new ModelReference();

        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertInstanceOf(ModelInterface::class, $modelReference->getModel());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::getId
     */
    public function testGetId()
    {
        $model = MyEmbeddedModel::create('id1');
        $model->setName('name1');

        $modelReference = new ModelReference();
        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertSame('id1', $modelReference->getId());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::getId
     */
    public function testGetIdNullReference()
    {
        $modelReference = new ModelReference();

        self::assertNull($modelReference->getInitialModel());
        self::assertNull($modelReference->getId());
    }
}
