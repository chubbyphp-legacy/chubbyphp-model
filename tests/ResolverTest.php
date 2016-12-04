<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Model\MissingRepositoryException;
use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Model\Resolver;
use Interop\Container\ContainerInterface;

final class ResolverTest extends \PHPUnit_Framework_TestCase
{
    use GetRepositoryTrait;

    public function testFindByMagicMethod()
    {
        $container = $this->getContainer([RepositoryInterface::class => $this->getRepository([])]);

        $resolver = new Resolver($container, [
            RepositoryInterface::class,
        ]);

        $returnValue = $resolver->findByMagicMethod(
            ModelInterface::class,
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

    public function testlazyFindByMagicMethod()
    {
        $container = $this->getContainer([RepositoryInterface::class => $this->getRepository([])]);

        $resolver = new Resolver($container, [
            RepositoryInterface::class,
        ]);

        $closure = $resolver->lazyFindByMagicMethod(
            ModelInterface::class,
            ['category' => 'category1'],
            ['name' => 'DESC'],
            1,
            1
        );

        self::assertInstanceOf(\Closure::class, $closure);

        $returnValue = $closure();

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
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
            ],
        ];

        $container = $this->getContainer([RepositoryInterface::class => $this->getRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            RepositoryInterface::class,
        ]);

        $model = $resolver->find(ModelInterface::class, $modelEntries[0]['id']);

        self::assertInstanceOf(ModelInterface::class, $model);

        self::assertSame($modelEntries[0]['id'], $model->getId());
        self::assertSame($modelEntries[0]['name'], $model->getName());
        self::assertSame($modelEntries[0]['category'], $model->getCategory());
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
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
            ],
        ];

        $container = $this->getContainer([RepositoryInterface::class => $this->getRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            RepositoryInterface::class,
        ]);

        $model = $resolver->findOneBy(ModelInterface::class, ['category' => 'category1'], ['name' => 'ASC']);

        self::assertInstanceOf(ModelInterface::class, $model);

        self::assertSame($modelEntries[2]['id'], $model->getId());
        self::assertSame($modelEntries[2]['name'], $model->getName());
        self::assertSame($modelEntries[2]['category'], $model->getCategory());
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
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
            ],
        ];

        $container = $this->getContainer([RepositoryInterface::class => $this->getRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            RepositoryInterface::class,
        ]);

        $models = $resolver->findBy(
            ModelInterface::class,
            ['category' => 'category1'],
            ['name' => 'DESC'],
            1,
            1
        );

        self::assertCount(1, $models);

        /** @var ModelInterface $model */
        $model = reset($models);

        self::assertInstanceOf(ModelInterface::class, $model);

        self::assertSame($modelEntries[2]['id'], $model->getId());
        self::assertSame($modelEntries[2]['name'], $model->getName());
        self::assertSame($modelEntries[2]['category'], $model->getCategory());
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::lazyFind
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     */
    public function testLazyFind()
    {
        $modelEntries = [
            [
                'id' => 'id1',
                'name' => 'name3',
                'category' => 'category1',
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
            ],
        ];

        $container = $this->getContainer([RepositoryInterface::class => $this->getRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            RepositoryInterface::class,
        ]);

        $closure = $resolver->lazyFind(ModelInterface::class, $modelEntries[0]['id']);

        /** @var ModelInterface $model */
        $model = $closure();

        self::assertInstanceOf(ModelInterface::class, $model);

        self::assertSame($modelEntries[0]['id'], $model->getId());
        self::assertSame($modelEntries[0]['name'], $model->getName());
        self::assertSame($modelEntries[0]['category'], $model->getCategory());
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::lazyFindOneBy
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     */
    public function testLazyFindOneBy()
    {
        $modelEntries = [
            [
                'id' => 'id1',
                'name' => 'name3',
                'category' => 'category1',
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
            ],
        ];

        $container = $this->getContainer([RepositoryInterface::class => $this->getRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            RepositoryInterface::class,
        ]);

        $closure = $resolver->lazyFindOneBy(ModelInterface::class, ['category' => 'category1'], ['name' => 'ASC']);

        /** @var ModelInterface $model */
        $model = $closure();

        self::assertInstanceOf(ModelInterface::class, $model);

        self::assertSame($modelEntries[2]['id'], $model->getId());
        self::assertSame($modelEntries[2]['name'], $model->getName());
        self::assertSame($modelEntries[2]['category'], $model->getCategory());
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::lazyFindBy
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     */
    public function testLazyFindBy()
    {
        $modelEntries = [
            [
                'id' => 'id1',
                'name' => 'name3',
                'category' => 'category1',
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
            ],
        ];

        $container = $this->getContainer([RepositoryInterface::class => $this->getRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            RepositoryInterface::class,
        ]);

        $closure = $resolver->lazyFindBy(
            ModelInterface::class,
            ['category' => 'category1'],
            ['name' => 'DESC'],
            1,
            1
        );

        $models = $closure();

        self::assertCount(1, $models);

        /** @var ModelInterface $model */
        $model = reset($models);

        self::assertInstanceOf(ModelInterface::class, $model);

        self::assertSame($modelEntries[2]['id'], $model->getId());
        self::assertSame($modelEntries[2]['name'], $model->getName());
        self::assertSame($modelEntries[2]['category'], $model->getCategory());
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::persist
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     */
    public function testInsert()
    {
        $container = $this->getContainer([RepositoryInterface::class => $this->getRepository([])]);

        $resolver = new Resolver($container, [
            RepositoryInterface::class,
        ]);

        $model = $this->getModel('id1')->setName('name1')->setCategory('category1');

        self::assertNull($resolver->find(ModelInterface::class, 'id1'));

        $resolver->persist($model);

        self::assertInstanceOf(ModelInterface::class, $resolver->find(ModelInterface::class, 'id1'));
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::persist
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     */
    public function testUpdate()
    {
        $modelEntries = [
            [
                'id' => 'id1',
                'name' => 'name3',
                'category' => 'category1',
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
            ],
        ];

        $container = $this->getContainer([RepositoryInterface::class => $this->getRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            RepositoryInterface::class,
        ]);

        $model = $resolver->find(ModelInterface::class, $modelEntries[0]['id']);

        self::assertInstanceOf(ModelInterface::class, $model);

        $model->setName('name5');

        $resolver->persist($model);

        $model = $resolver->find(ModelInterface::class, $modelEntries[0]['id']);

        self::assertInstanceOf(ModelInterface::class, $model);

        self::assertSame('name5', $model->getName());
    }

    /**
     * @covers \Chubbyphp\Model\Resolver::__construct
     * @covers \Chubbyphp\Model\Resolver::remove
     * @covers \Chubbyphp\Model\Resolver::getRepositoryByClass
     */
    public function testRemove()
    {
        $modelEntries = [
            [
                'id' => 'id1',
                'name' => 'name3',
                'category' => 'category1',
            ],
            [
                'id' => 'id2',
                'name' => 'name2',
                'category' => 'category2',
            ],
            [
                'id' => 'id3',
                'name' => 'name1',
                'category' => 'category1',
            ],
        ];

        $container = $this->getContainer([RepositoryInterface::class => $this->getRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            RepositoryInterface::class,
        ]);

        $model = $resolver->find(ModelInterface::class, $modelEntries[0]['id']);

        self::assertInstanceOf(ModelInterface::class, $model);

        $resolver->remove($model);

        $model = $resolver->find(ModelInterface::class, $modelEntries[0]['id']);

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
        self::expectExceptionMessage(sprintf('Missing repository for model "%s"', User::class));

        $container = $this->getContainer([]);

        $resolver = new Resolver($container, []);

        $resolver->find(User::class, 'someid');
    }

    /**
     * @param array $services
     *
     * @return ContainerInterface
     */
    private function getContainer(array $services): ContainerInterface
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->setMethods(['get'])
            ->getMockForAbstractClass();

        $container
            ->expects(self::any())
            ->method('get')
            ->willReturnCallback(function (string $key) use ($services) {
                return $services[$key];
            });

        return $container;
    }
}
