<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Model\ModelInterface;

trait GetModelTrait
{
    /**
     * @param string $id
     *
     * @return ModelInterface
     */
    private function getModel(string $id): ModelInterface
    {
        /** @var ModelInterface|\PHPUnit_Framework_MockObject_MockObject $model */
        $model = $this->getMockBuilder(ModelInterface::class)->setMethods([
            'getId',
            'setName',
            'getName',
            'setCategory',
            'getCategory',
            'jsonSerialize',
            'toPersistence',
        ])->getMockForAbstractClass();

        $model->__id = $id;
        $model->__name = null;
        $model->__category = null;

        $model->expects(self::any())->method('getId')->willReturnCallback(function () use ($model) {
            return $model->__id;
        });

        $model->expects(self::any())->method('setName')->willReturnCallback(function (string $name) use ($model) {
            $model->__name = $name;

            return $model;
        });

        $model->expects(self::any())->method('getName')->willReturnCallback(function () use ($model) {
            return $model->__name;
        });

        $model->expects(self::any())->method('setCategory')->willReturnCallback(function (string $category) use ($model) {
            $model->__category = $category;

            return $model;
        });

        $model->expects(self::any())->method('getCategory')->willReturnCallback(function () use ($model) {
            return $model->__category;
        });

        $model->expects(self::any())->method('jsonSerialize')->willReturnCallback(function () use ($model) {
            return [
                'id' => $model->__id,
                'name' => $model->__name,
                'category' => $model->__category,
            ];
        });

        $model->expects(self::any())->method('toPersistence')->willReturnCallback(function () use ($model) {
            return [
                'id' => $model->__id,
                'name' => $model->__name,
                'category' => $model->__category,
            ];
        });

        return $model;
    }
}
