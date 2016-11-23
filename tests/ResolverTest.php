<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Model\MissingRepositoryException;
use Chubbyphp\Model\Resolver;
use Chubbyphp\Tests\Model\Resources\User;
use Chubbyphp\Tests\Model\Resources\UserRepository;
use Interop\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;

/**
 * @covers Chubbyphp\Model\Resolver
 */
final class ResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testFind()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname3@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname2@domain.tld',
                'password' => 'verysecurepassword',
                'active' => false,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname1@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $container = $this->getContainer([UserRepository::class => new UserRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            UserRepository::getModelClass() => UserRepository::class,
        ]);

        $user = $resolver->find(UserRepository::getModelClass(), $modelEntries[0]['id']);

        self::assertInstanceOf(UserRepository::getModelClass(), $user);

        self::assertSame($modelEntries[0]['id'], $user->getId());
        self::assertSame($modelEntries[0]['username'], $user->getUsername());
        self::assertSame($modelEntries[0]['password'], $user->getPassword());
        self::assertSame($modelEntries[0]['active'], $user->isActive());
    }

    public function testFindOneBy()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname3@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname2@domain.tld',
                'password' => 'verysecurepassword',
                'active' => false,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname1@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $container = $this->getContainer([UserRepository::class => new UserRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            UserRepository::getModelClass() => UserRepository::class,
        ]);

        $user = $resolver->findOneBy(UserRepository::getModelClass(), ['username' => 'nickname2@domain.tld']);

        self::assertInstanceOf(UserRepository::getModelClass(), $user);

        self::assertSame($modelEntries[1]['id'], $user->getId());
        self::assertSame($modelEntries[1]['username'], $user->getUsername());
        self::assertSame($modelEntries[1]['password'], $user->getPassword());
        self::assertSame($modelEntries[1]['active'], $user->isActive());
    }

    public function testFindBy()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname3@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname2@domain.tld',
                'password' => 'verysecurepassword',
                'active' => false,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname1@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $container = $this->getContainer([UserRepository::class => new UserRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            UserRepository::getModelClass() => UserRepository::class,
        ]);

        $users = $resolver->findBy(
            UserRepository::getModelClass(),
            ['password' => 'verysecurepassword'],
            ['username' => 'DESC'],
            1,
            1
        );

        self::assertCount(1, $users);

        $user = reset($users);

        self::assertInstanceOf(UserRepository::getModelClass(), $user);

        self::assertSame($modelEntries[1]['id'], $user->getId());
        self::assertSame($modelEntries[1]['username'], $user->getUsername());
        self::assertSame($modelEntries[1]['password'], $user->getPassword());
        self::assertSame($modelEntries[1]['active'], $user->isActive());
    }

    public function testLazyFind()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname3@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname2@domain.tld',
                'password' => 'verysecurepassword',
                'active' => false,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname1@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $container = $this->getContainer([UserRepository::class => new UserRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            UserRepository::getModelClass() => UserRepository::class,
        ]);

        $closure = $resolver->lazyFind(UserRepository::getModelClass(), $modelEntries[0]['id']);

        self::assertInstanceOf(\Closure::class, $closure);

        $user = $closure();

        self::assertInstanceOf(UserRepository::getModelClass(), $user);

        self::assertSame($modelEntries[0]['id'], $user->getId());
        self::assertSame($modelEntries[0]['username'], $user->getUsername());
        self::assertSame($modelEntries[0]['password'], $user->getPassword());
        self::assertSame($modelEntries[0]['active'], $user->isActive());
    }

    public function testLazyFindOneBy()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname3@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname2@domain.tld',
                'password' => 'verysecurepassword',
                'active' => false,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname1@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $container = $this->getContainer([UserRepository::class => new UserRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            UserRepository::getModelClass() => UserRepository::class,
        ]);

        $closure = $resolver->lazyFindOneBy(UserRepository::getModelClass(), ['username' => 'nickname2@domain.tld']);

        self::assertInstanceOf(\Closure::class, $closure);

        $user = $closure();

        self::assertInstanceOf(UserRepository::getModelClass(), $user);

        self::assertSame($modelEntries[1]['id'], $user->getId());
        self::assertSame($modelEntries[1]['username'], $user->getUsername());
        self::assertSame($modelEntries[1]['password'], $user->getPassword());
        self::assertSame($modelEntries[1]['active'], $user->isActive());
    }

    public function testLazyFindBy()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname3@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname2@domain.tld',
                'password' => 'verysecurepassword',
                'active' => false,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname1@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $container = $this->getContainer([UserRepository::class => new UserRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            UserRepository::getModelClass() => UserRepository::class,
        ]);

        $closure = $resolver->lazyFindBy(
            UserRepository::getModelClass(),
            ['password' => 'verysecurepassword'],
            ['username' => 'DESC'],
            1,
            1
        );

        self::assertInstanceOf(\Closure::class, $closure);

        $users = $closure();

        self::assertCount(1, $users);

        $user = reset($users);

        self::assertInstanceOf(UserRepository::getModelClass(), $user);

        self::assertSame($modelEntries[1]['id'], $user->getId());
        self::assertSame($modelEntries[1]['username'], $user->getUsername());
        self::assertSame($modelEntries[1]['password'], $user->getPassword());
        self::assertSame($modelEntries[1]['active'], $user->isActive());
    }

    public function testPersist()
    {
        $container = $this->getContainer([UserRepository::class => new UserRepository([])]);

        $resolver = new Resolver($container, [
            UserRepository::getModelClass() => UserRepository::class,
        ]);

        $id = (string) Uuid::uuid4();

        $user = new User($id);
        $user->setUsername('user1d');
        $user->setPassword('verysecurepassword');
        $user->setActive(true);

        self::assertNull($resolver->find(User::class, $id));

        $resolver->persist($user);

        self::assertInstanceOf(User::class, $resolver->find(User::class, $id));
    }

    public function testUpdate()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname3@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname2@domain.tld',
                'password' => 'verysecurepassword',
                'active' => false,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname1@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $container = $this->getContainer([UserRepository::class => new UserRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            UserRepository::getModelClass() => UserRepository::class,
        ]);

        /** @var User $user */
        $user = $resolver->find(User::class, $modelEntries[0]['id']);

        self::assertInstanceOf(User::class, $user);

        $user->setUsername('nickname@domain.tld');

        $resolver->persist($user);

        /** @var User $user */
        $user = $resolver->find(User::class, $modelEntries[0]['id']);

        self::assertInstanceOf(User::class, $user);

        self::assertSame('nickname@domain.tld', $user->getUsername());
    }

    public function testRemove()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname3@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname2@domain.tld',
                'password' => 'verysecurepassword',
                'active' => false,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname1@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $container = $this->getContainer([UserRepository::class => new UserRepository($modelEntries)]);

        $resolver = new Resolver($container, [
            UserRepository::getModelClass() => UserRepository::class,
        ]);

        /** @var User $user */
        $user = $resolver->find(User::class, $modelEntries[0]['id']);

        self::assertInstanceOf(User::class, $user);

        $resolver->remove($user);

        /** @var User $user */
        $user = $resolver->find(User::class, $modelEntries[0]['id']);

        self::assertNull($user);
    }

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
