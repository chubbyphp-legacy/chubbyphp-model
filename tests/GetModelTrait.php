<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Model\Collection\ModelCollection;
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
            'setOneToOne',
            'getOneToOne',
            'setOneToMany',
            'getOneToMany',
            'jsonSerialize',
            'toPersistence',
        ])->getMockForAbstractClass();

        $model->__id = $id;
        $model->__name = null;
        $model->__category = null;
        $model->__oneToOne = null;
        $model->__oneToMany = new ModelCollection();

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

        $model->expects(self::any())->method('setOneToOne')->willReturnCallback(function (ModelInterface $relatedModel) use ($model) {
            $model->__oneToOne = $relatedModel;

            return $model;
        });

        $model->expects(self::any())->method('getOneToOne')->willReturnCallback(function () use ($model) {
            return $model->__oneToOne;
        });

        $model->expects(self::any())->method('setOneToMany')->willReturnCallback(function (array $relatedModels) use ($model) {
            $model->__oneToMany->setModels($relatedModels);

            return $model;
        });

        $model->expects(self::any())->method('getOneToMany')->willReturnCallback(function () use ($model) {
            return $model->__oneToMany->getModels();
        });

        $model->expects(self::any())->method('jsonSerialize')->willReturnCallback(function () use ($model) {
            return [
                'id' => $model->__id,
                'name' => $model->__name,
                'category' => $model->__category,
                'oneToOne' => null !== $model->__oneToOne ? $model->__oneToOne->jsonSerialize() : null,
                'oneToMany' => $model->__oneToMany->jsonSerialize()
            ];
        });

        $model->expects(self::any())->method('toPersistence')->willReturnCallback(function () use ($model) {
            return [
                'id' => $model->__id,
                'name' => $model->__name,
                'category' => $model->__category,
                'oneToOneId' => null !== $model->__oneToOne ? $model->__oneToOne->getId() : null,
                'oneToMany' => $model->__oneToMany
            ];
        });

        return $model;
    }
}
