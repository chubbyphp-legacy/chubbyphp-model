<?php

namespace Chubbyphp\Tests\Model\Reference;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\Reference\LazyModelReference;
use Chubbyphp\Tests\Model\GetModelTrait;

final class LazyModelReferenceTest extends \PHPUnit_Framework_TestCase
{
    use GetModelTrait;

    /**
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::resolveModel
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::setModel
     */
    public function testSetModel()
    {
        $model = $this->getModel('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelReference = new LazyModelReference(function () {
            return null;
        });

        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertInstanceOf(ModelInterface::class, $modelReference->getModel());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::resolveModel
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::getInitialModel
     */
    public function testGetInitialModel()
    {
        $model = $this->getModel('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelReference = new LazyModelReference(function () {
            return null;
        });

        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertInstanceOf(ModelInterface::class, $modelReference->getModel());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::resolveModel
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::getModel
     */
    public function testGetModel()
    {
        $model = $this->getModel('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelReference = new LazyModelReference(function () {
            return null;
        });

        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertInstanceOf(ModelInterface::class, $modelReference->getModel());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::resolveModel
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::getId
     */
    public function testGetId()
    {
        $model = $this->getModel('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelReference = new LazyModelReference(function () {
            return null;
        });

        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertSame('id1', $modelReference->getId());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::resolveModel
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::getId
     */
    public function testGetIdNullReference()
    {
        $modelReference = new LazyModelReference(function () {
            return null;
        });

        self::assertNull($modelReference->getInitialModel());
        self::assertNull($modelReference->getId());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::resolveModel
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::jsonSerialize
     */
    public function testJsonSerialize()
    {
        $model = $this->getModel('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelReference = new LazyModelReference(function () use ($model) {
            return $model;
        });

        $modelAsArray = json_decode(json_encode($modelReference), true);

        self::assertSame('name1', $modelAsArray['name']);
        self::assertSame('category', $modelAsArray['category']);
    }

    /**
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::resolveModel
     * @covers \Chubbyphp\Model\Reference\LazyModelReference::jsonSerialize
     */
    public function testJsonSerializeNullReference()
    {
        $modelReference = new LazyModelReference(function () {
            return null;
        });

        self::assertNull(json_decode(json_encode($modelReference), true));
    }
}
