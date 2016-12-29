<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Model\MissingRepositoryException;
use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\RelatedModelManipulationStack;
use Chubbyphp\Model\Resolver;
use Interop\Container\ContainerInterface;
use MyProject\Model\MyEmbeddedModel;
use MyProject\Model\MyModel;
use MyProject\Repository\MyEmbeddedRepository;
use MyProject\Repository\MyModelRepository;
use Pimple\Container;

final class ResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testFindByMagicMethod()
    {
        $resolver = $this->getResolver();

        $returnValue = $resolver->findByMagicMethod(
            MyModel::class,
            ['category' => 'category1'],
            ['name' => 'DESC'],
            1,
            1
        );

        self::assertSame([
            ['category' => 'category1'],
            ['name' => 'DESC'],
            1,
            1,
        ], $returnValue);
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::find
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     */
    public function testFind()
    {
        $modelEntries = [
            [
                'id' => 'id1',
                'name' => 'name3',
                'category' => 'category1',
                'oneToOneId' => 'id1'
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
                'oneToOneId' => null
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
                'oneToOneId' => 'id3'
            ],
        ];

        $embeddedModelEntries = [
            [
                'id' => 'id1',
                'modelId' => 'id1',
                'name' => 'name3'
            ],
            [
                'id' => 'id2',
                'modelId' => 'id3',
                'name' => 'name2'
            ],
            [
                'id' => 'id3',
                'modelId' => 'id1',
                'name' => 'name1'
            ],
        ];

        $resolver = $this->getResolver($modelEntries, $embeddedModelEntries);

        /** @var MyModel $model */
        $model = $resolver->find(MyModel::class, $modelEntries[0]['id']);

        self::assertInstanceOf(MyModel::class, $model);

        self::assertSame($modelEntries[0]['id'], $model->getId());
        self::assertSame($modelEntries[0]['name'], $model->getName());
        self::assertSame($modelEntries[0]['category'], $model->getCategory());
        self::assertSame($modelEntries[0]['oneToOneId'], $model->getOneToOne()->getId());

        self::assertCount(2, $model->getOneToMany());

        self::assertSame($embeddedModelEntries[2]['id'], $model->getOneToMany()[0]->getId());
        self::assertSame($embeddedModelEntries[0]['id'], $model->getOneToMany()[1]->getId());
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::find
     */
    public function testFindWithNull()
    {
        $container = new Container();

        $container['resolver'] = function () use ($container) {
            return new Resolver($this->getInteropContainer($container), []);
        };

        /** @var Resolver $resolver */
        $resolver = $container['resolver'];

        self::assertNull($resolver->find(MyModel::class, null));
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::findOneBy
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     */
    public function testFindOneBy()
    {
        $modelEntries = [
            [
                'id' => 'id1',
                'name' => 'name3',
                'category' => 'category1',
                'oneToOneId' => 'id1'
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
                'oneToOneId' => null
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
                'oneToOneId' => 'id3'
            ],
        ];

        $embeddedModelEntries = [
            [
                'id' => 'id1',
                'modelId' => 'id1',
                'name' => 'name3'
            ],
            [
                'id' => 'id2',
                'modelId' => 'id3',
                'name' => 'name2'
            ],
            [
                'id' => 'id3',
                'modelId' => 'id1',
                'name' => 'name1'
            ],
        ];

        $resolver = $this->getResolver($modelEntries, $embeddedModelEntries);

        /** @var MyModel $model */
        $model = $resolver->findOneBy(MyModel::class, ['category' => 'category1'], ['name' => 'ASC']);

        self::assertInstanceOf(ModelInterface::class, $model);

        self::assertSame($modelEntries[2]['id'], $model->getId());
        self::assertSame($modelEntries[2]['name'], $model->getName());
        self::assertSame($modelEntries[2]['category'], $model->getCategory());
        self::assertSame($modelEntries[2]['oneToOneId'], $model->getOneToOne()->getId());

        self::assertCount(1, $model->getOneToMany());

        self::assertSame($embeddedModelEntries[1]['id'], $model->getOneToMany()[0]->getId());
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::findBy
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     */
    public function testFindBy()
    {
        $modelEntries = [
            [
                'id' => 'id1',
                'name' => 'name3',
                'category' => 'category1',
                'oneToOneId' => 'id1'
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
                'oneToOneId' => null
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
                'oneToOneId' => 'id3'
            ],
        ];

        $embeddedModelEntries = [
            [
                'id' => 'id1',
                'modelId' => 'id1',
                'name' => 'name3'
            ],
            [
                'id' => 'id2',
                'modelId' => 'id3',
                'name' => 'name2'
            ],
            [
                'id' => 'id3',
                'modelId' => 'id1',
                'name' => 'name1'
            ],
        ];

        $resolver = $this->getResolver($modelEntries, $embeddedModelEntries);

        $models = $resolver->findBy(
            MyModel::class,
            ['category' => 'category1'],
            ['name' => 'DESC'],
            1,
            1
        );

        self::assertCount(1, $models);

        /** @var MyModel $model */
        $model = reset($models);

        self::assertInstanceOf(ModelInterface::class, $model);

        self::assertSame($modelEntries[2]['id'], $model->getId());
        self::assertSame($modelEntries[2]['name'], $model->getName());
        self::assertSame($modelEntries[2]['category'], $model->getCategory());
        self::assertSame($modelEntries[2]['oneToOneId'], $model->getOneToOne()->getId());

        self::assertCount(1, $model->getOneToMany());

        self::assertSame($embeddedModelEntries[1]['id'], $model->getOneToMany()[0]->getId());
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::persist
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     * @covers \Chubbyphp\Model\RelatedModelManipulationStack
     */
    public function testInsert()
    {
        $resolver = $this->getResolver();

        self::assertNull($resolver->find(MyModel::class, 'id1'));

        $model = MyModel::create('id1')
            ->setName('name1')
            ->setCategory('category1')
            ->setOneToOne(MyEmbeddedModel::create('id1')->setName('name1'))
            ->setOneToMany([
                MyEmbeddedModel::create('id1')->setName('name1'),
                MyEmbeddedModel::create('id2')->setName('name2')
            ])
        ;

        $resolver->persist($model);

        /** @var MyModel $modelFromRepository */
        $modelFromRepository = $resolver->find(MyModel::class, 'id1');

        self::assertInstanceOf(MyModel::class, $modelFromRepository);

        self::assertSame($model->getId(), $modelFromRepository->getId());
        self::assertSame($model->getName(), $modelFromRepository->getName());
        self::assertSame($model->getCategory(), $modelFromRepository->getCategory());
        self::assertEquals($model->getOneToOne(), $modelFromRepository->getOneToOne());
        self::assertEquals($model->getOneToMany(), $modelFromRepository->getOneToMany());
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::persist
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     * @covers \Chubbyphp\Model\RelatedModelManipulationStack
     */
    public function testUpdate()
    {
        $modelEntries = [
            [
                'id' => 'id1',
                'name' => 'name3',
                'category' => 'category1',
                'oneToOneId' => 'id1'
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
                'oneToOneId' => null
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
                'oneToOneId' => 'id3'
            ],
        ];

        $embeddedModelEntries = [
            [
                'id' => 'id1',
                'modelId' => 'id1',
                'name' => 'name3'
            ],
            [
                'id' => 'id2',
                'modelId' => 'id3',
                'name' => 'name2'
            ],
            [
                'id' => 'id3',
                'modelId' => 'id1',
                'name' => 'name1'
            ],
        ];

        $resolver = $this->getResolver($modelEntries, $embeddedModelEntries);

        /** @var MyModel $model */
        $model = $resolver->find(MyModel::class, $modelEntries[0]['id']);

        self::assertInstanceOf(MyModel::class, $model);

        $model->setName('name5');

        $resolver->persist($model);

        /** @var MyModel $model */
        $model = $resolver->find(MyModel::class, $modelEntries[0]['id']);

        self::assertInstanceOf(MyModel::class, $model);

        self::assertSame('name5', $model->getName());
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::remove
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     * @covers \Chubbyphp\Model\RelatedModelManipulationStack
     */
    public function testRemove()
    {
        $modelEntries = [
            [
                'id' => 'id1',
                'name' => 'name3',
                'category' => 'category1',
                'oneToOneId' => 'id1'
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
                'oneToOneId' => null
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
                'oneToOneId' => 'id3'
            ],
        ];

        $embeddedModelEntries = [
            [
                'id' => 'id1',
                'modelId' => 'id1',
                'name' => 'name3'
            ],
            [
                'id' => 'id2',
                'modelId' => 'id3',
                'name' => 'name2'
            ],
            [
                'id' => 'id3',
                'modelId' => 'id1',
                'name' => 'name1'
            ],
        ];

        $resolver = $this->getResolver($modelEntries, $embeddedModelEntries);

        /** @var MyModel $model */
        $model = $resolver->find(MyModel::class, $modelEntries[0]['id']);

        self::assertInstanceOf(MyModel::class, $model);

        $resolver->remove($model);

        /** @var MyModel $model */
        $model = $resolver->find(MyModel::class, $modelEntries[0]['id']);

        self::assertNull($model);
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::find
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     */
    public function testUnknownModel()
    {
        self::expectException(MissingRepositoryException::class);
        self::expectExceptionMessage(sprintf('Missing repository for model "%s"', MyModel::class));

        $resolver = new Resolver($this->getInteropContainer(new Container()), []);

        $resolver->find(MyModel::class, 'someid');
    }

    /**
     * @param Container $container
     * @return ContainerInterface
     */
    private function getInteropContainer(Container $container): ContainerInterface
    {
        /** @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject $interopContainer */
        $interopContainer = $this->getMockBuilder(ContainerInterface::class)
            ->setMethods(['get'])
            ->getMockForAbstractClass();

        $interopContainer->__container = $container;

        $interopContainer
            ->expects(self::any())
            ->method('get')
            ->willReturnCallback(function (string $key) use ($interopContainer) {
                return $interopContainer->__container[$key];
            });

        return $interopContainer;
    }

    /**
     * @param array $modelEntries
     * @param array $embeddedModelEntries
     * @return Resolver
     */
    private function getResolver(array $modelEntries = [], array $embeddedModelEntries = []): Resolver
    {
        $container = new Container();

        $container['resolver'] = function () use ($container) {
            return new Resolver($this->getInteropContainer($container), [
                MyModelRepository::class,
                MyEmbeddedRepository::class
            ]);
        };

        $container[MyModelRepository::class] = function () use ($container, $modelEntries) {
            return new MyModelRepository($modelEntries, $container['resolver']);
        };

        $container[MyEmbeddedRepository::class] = function () use ($container, $embeddedModelEntries) {
            return new MyEmbeddedRepository($embeddedModelEntries, $container['resolver']);
        };

        return $container['resolver'];
    }
}
