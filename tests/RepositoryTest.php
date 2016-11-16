<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Tests\Model\Resources\User;
use Chubbyphp\Tests\Model\Resources\UserRepository;
use Ramsey\Uuid\Uuid;

final class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetModelClass()
    {
        $repo = new UserRepository();

        self::assertSame(User::class, $repo->getModelClass());
    }

    public function testFind()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'user1d',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $repo = new UserRepository($modelEntries);

        self::assertNull($repo->find('unknown'));

        /** @var User $user */
        $user = $repo->find($modelEntries[0]['id']);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelEntries[0]['id'], $user->getId());
        self::assertSame($modelEntries[0]['username'], $user->getUsername());
        self::assertSame($modelEntries[0]['password'], $user->getPassword());
        self::assertSame($modelEntries[0]['active'], $user->isActive());
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

        $repo = new UserRepository($modelEntries);

        $users = $repo->findBy([]);

        self::assertCount(3, $users);

        /** @var User $user */
        $user = array_shift($users);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelEntries[0]['id'], $user->getId());
        self::assertSame($modelEntries[0]['username'], $user->getUsername());
        self::assertSame($modelEntries[0]['password'], $user->getPassword());
        self::assertSame($modelEntries[0]['active'], $user->isActive());

        /** @var User $user */
        $user = array_shift($users);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelEntries[1]['id'], $user->getId());
        self::assertSame($modelEntries[1]['username'], $user->getUsername());
        self::assertSame($modelEntries[1]['password'], $user->getPassword());
        self::assertSame($modelEntries[1]['active'], $user->isActive());

        /** @var User $user */
        $user = array_shift($users);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelEntries[2]['id'], $user->getId());
        self::assertSame($modelEntries[2]['username'], $user->getUsername());
        self::assertSame($modelEntries[2]['password'], $user->getPassword());
        self::assertSame($modelEntries[2]['active'], $user->isActive());
    }

    public function testFindByActive()
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

        $repo = new UserRepository($modelEntries);

        $activeUsers = $repo->findBy(['active' => true]);

        self::assertCount(2, $activeUsers);

        /** @var User $activeUser */
        $activeUser = array_shift($activeUsers);

        self::assertInstanceOf(User::class, $activeUser);
        self::assertSame($modelEntries[0]['id'], $activeUser->getId());
        self::assertSame($modelEntries[0]['username'], $activeUser->getUsername());
        self::assertSame($modelEntries[0]['password'], $activeUser->getPassword());
        self::assertSame($modelEntries[0]['active'], $activeUser->isActive());

        /** @var User $activeUser */
        $activeUser = array_shift($activeUsers);

        self::assertInstanceOf(User::class, $activeUser);
        self::assertSame($modelEntries[2]['id'], $activeUser->getId());
        self::assertSame($modelEntries[2]['username'], $activeUser->getUsername());
        self::assertSame($modelEntries[2]['password'], $activeUser->getPassword());
        self::assertSame($modelEntries[2]['active'], $activeUser->isActive());
    }

    public function testFindByInactive()
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

        $repo = new UserRepository($modelEntries);

        $inactiveUsers = $repo->findBy(['active' => false]);

        self::assertCount(1, $inactiveUsers);

        /** @var User $inactiveUser */
        $inactiveUser = array_shift($inactiveUsers);

        self::assertInstanceOf(User::class, $inactiveUser);
        self::assertSame($modelEntries[1]['id'], $inactiveUser->getId());
        self::assertSame($modelEntries[1]['username'], $inactiveUser->getUsername());
        self::assertSame($modelEntries[1]['password'], $inactiveUser->getPassword());
        self::assertSame($modelEntries[1]['active'], $inactiveUser->isActive());
    }

    public function testFindByWithOrderByUsername()
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

        $repo = new UserRepository($modelEntries);

        $users = $repo->findBy([], ['username' => 'ASC']);

        self::assertCount(3, $users);

        /** @var User $user */
        $user = array_shift($users);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelEntries[2]['id'], $user->getId());
        self::assertSame($modelEntries[2]['username'], $user->getUsername());
        self::assertSame($modelEntries[2]['password'], $user->getPassword());
        self::assertSame($modelEntries[2]['active'], $user->isActive());

        /** @var User $user */
        $user = array_shift($users);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelEntries[1]['id'], $user->getId());
        self::assertSame($modelEntries[1]['username'], $user->getUsername());
        self::assertSame($modelEntries[1]['password'], $user->getPassword());
        self::assertSame($modelEntries[1]['active'], $user->isActive());

        /** @var User $user */
        $user = array_shift($users);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelEntries[0]['id'], $user->getId());
        self::assertSame($modelEntries[0]['username'], $user->getUsername());
        self::assertSame($modelEntries[0]['password'], $user->getPassword());
        self::assertSame($modelEntries[0]['active'], $user->isActive());
    }

    public function testFindByWithOrderByUsernameAndActive()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname2@domain.tld',
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

        $repo = new UserRepository($modelEntries);

        $users = $repo->findBy([], ['username' => 'ASC', 'active' => 'ASC']);

        self::assertCount(3, $users);

        /** @var User $user */
        $user = array_shift($users);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelEntries[2]['id'], $user->getId());
        self::assertSame($modelEntries[2]['username'], $user->getUsername());
        self::assertSame($modelEntries[2]['password'], $user->getPassword());
        self::assertSame($modelEntries[2]['active'], $user->isActive());

        /** @var User $user */
        $user = array_shift($users);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelEntries[1]['id'], $user->getId());
        self::assertSame($modelEntries[1]['username'], $user->getUsername());
        self::assertSame($modelEntries[1]['password'], $user->getPassword());
        self::assertSame($modelEntries[1]['active'], $user->isActive());

        /** @var User $user */
        $user = array_shift($users);

        self::assertInstanceOf(User::class, $user);
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

        $repo = new UserRepository($modelEntries);

        /** @var User $user */
        $user = $repo->findOneBy(['username' => 'nickname1@domain.tld']);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelEntries[2]['id'], $user->getId());
        self::assertSame($modelEntries[2]['username'], $user->getUsername());
        self::assertSame($modelEntries[2]['password'], $user->getPassword());
        self::assertSame($modelEntries[2]['active'], $user->isActive());
    }

    public function testPersist()
    {
        $repo = new UserRepository();

        $id = (string) Uuid::uuid4();

        $user = new User($id);
        $user->setUsername('user1d');
        $user->setPassword('verysecurepassword');
        $user->setActive(true);

        self::assertNull($repo->find($id));

        $repo->persist($user);

        self::assertInstanceOf(User::class, $repo->find($id));
    }

    public function testUpdate()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'user1d',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $repo = new UserRepository($modelEntries);

        /** @var User $user */
        $user = $repo->find($modelEntries[0]['id']);

        self::assertInstanceOf(User::class, $user);

        $user->setUsername('nickname@domain.tld');

        $repo->persist($user);

        /** @var User $user */
        $user = $repo->find($modelEntries[0]['id']);

        self::assertInstanceOf(User::class, $user);

        self::assertSame('nickname@domain.tld', $user->getUsername());
    }

    public function testRemove()
    {
        $modelEntries = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'user1d',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $repo = new UserRepository($modelEntries);

        /** @var User $user */
        $user = $repo->find($modelEntries[0]['id']);

        $repo->remove($user);
    }
}
