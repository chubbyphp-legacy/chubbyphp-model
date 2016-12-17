<?php

namespace Chubbyphp\Tests\Model\Reference;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\Reference\ModelReference;
use Chubbyphp\Tests\Model\GetModelTrait;

final class ModelReferenceTest extends \PHPUnit_Framework_TestCase
{
    use GetModelTrait;

    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\ModelReference::setModel
     */
    public function testSetModel()
    {
        $model = $this->getModel('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelReference = new ModelReference();

        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertInstanceOf(ModelInterface::class, $modelReference->getModel());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\ModelReference::getInitialModel
     */
    public function testGetInitialModel()
    {
        $model = $this->getModel('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelReference = new ModelReference();

        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertInstanceOf(ModelInterface::class, $modelReference->getModel());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\ModelReference::getModel
     */
    public function testGetModel()
    {
        $model = $this->getModel('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelReference = new ModelReference();

        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertInstanceOf(ModelInterface::class, $modelReference->getModel());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\ModelReference::getId
     */
    public function testGetId()
    {
        $model = $this->getModel('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelReference = new ModelReference();

        $modelReference->setModel($model);

        self::assertNull($modelReference->getInitialModel());
        self::assertSame('id1', $modelReference->getId());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\ModelReference::getId
     */
    public function testGetIdNullReference()
    {
        $modelReference = new ModelReference();

        self::assertNull($modelReference->getInitialModel());
        self::assertNull($modelReference->getId());
    }

    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\ModelReference::jsonSerialize
     */
    public function testJsonSerialize()
    {
        $model = $this->getModel('id1');
        $model->setName('name1');
        $model->setCategory('category');

        $modelReference = new ModelReference($model);

        $modelAsArray = json_decode(json_encode($modelReference), true);

        self::assertSame('name1', $modelAsArray['name']);
        self::assertSame('category', $modelAsArray['category']);
    }

    /**
     * @covers \Chubbyphp\Model\Reference\ModelReference::__construct
     * @covers \Chubbyphp\Model\Reference\ModelReference::jsonSerialize
     */
    public function testJsonSerializeNullReference()
    {
        $modelReference = new ModelReference();

        self::assertNull(json_decode(json_encode($modelReference), true));
    }
}
