<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\RepositoryInterface;

trait GetRepositoryTrait
{
    use GetModelTrait;

    /**
     * @param array $modelEntries
     *
     * @return RepositoryInterface
     */
    private function getRepository(array $modelEntries = []): RepositoryInterface
    {
        /** @var RepositoryInterface|\PHPUnit_Framework_MockObject_MockObject $repository */
        $repository = $this->getMockBuilder(RepositoryInterface::class)->setMethods([
            'isResponsible',
            'find',
            'findOneBy',
            'findBy',
            'persist',
            'remove',
            'clear',
        ])->getMockForAbstractClass();

        $repository->__modelEntries = [];

        foreach ($modelEntries as $modelEntry) {
            $repository->__modelEntries[$modelEntry['id']] = $modelEntry;
        }

        $repository->expects(self::any())->method('isResponsible')->willReturn(true);

        $repository->expects(self::any())->method('find')->willReturnCallback(function (string $id) use ($repository) {
            if (!isset($repository->__modelEntries[$id])) {
                return null;
            }

            $modelEntry = $repository->__modelEntries[$id];

            return $this->getModel($id)->setName($modelEntry['name'])->setCategory($modelEntry['category']);
        });

        $repository->expects(self::any())->method('findOneBy')->willReturnCallback(
            function (array $criteria, array $orderBy = null) use ($repository) {
                $models = $repository->findBy($criteria, $orderBy, 1, 0);

                if ([] === $models) {
                    return null;
                }

                return reset($models);
            }
        );

        $repository->expects(self::any())->method('findBy')->willReturnCallback(
            function (array $criteria, array $orderBy = null, int $limit = null, int $offset = null) use ($repository) {
                if ([] === $repository->__modelEntries) {
                    return [];
                }

                $models = [];
                foreach ($repository->__modelEntries as $id => $modelEntry) {
                    foreach ($criteria as $key => $value) {
                        if ($modelEntry[$key] !== $value) {
                            continue 2;
                        }
                    }

                    $models[] = $this->getModel($id)->setName($modelEntry['name'])->setCategory($modelEntry['category']);
                }

                if (null !== $orderBy) {
                    usort($models, function (ModelInterface $a, ModelInterface $b) use ($orderBy) {
                        foreach ($orderBy as $key => $value) {
                            $property = '__'.$key;
                            $sorting = strcmp($a->$property, $b->$property);
                            if ($value === 'DESC') {
                                $sorting = $sorting * -1;
                            }

                            if (0 !== $sorting) {
                                return $sorting;
                            }
                        }

                        return 0;
                    });
                }

                if (null !== $limit && null !== $offset) {
                    return array_slice($models, $offset, $limit);
                }

                if (null !== $limit) {
                    return array_slice($models, 0, $limit);
                }

                return $models;
            }
        );

        $repository->expects(self::any())->method('persist')->willReturnCallback(
            function (ModelInterface $model) use ($repository) {
                $repository->__modelEntries[$model->getId()] = $model->toPersistence();

                return $repository;
            }
        );

        $repository->expects(self::any())->method('remove')->willReturnCallback(
            function (ModelInterface $model) use ($repository) {
                if (isset($repository->__modelEntries[$model->getId()])) {
                    unset($repository->__modelEntries[$model->getId()]);
                }

                return $repository;
            }
        );

        $repository->expects(self::any())->method('clear')->willReturnCallback(function () use ($repository) {
            $repository->__modelEntries = [];

            return $repository;
        });

        return $repository;
    }
}
