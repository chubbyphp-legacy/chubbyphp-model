<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Tests\Model\Resources\User;
use Chubbyphp\Tests\Model\Resources\UserRepository;
use Ramsey\Uuid\Uuid;

final class RepositoryTest extends \PHPUnit_Framework_TestCase
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
        $user = $repo->findOneBy(['active' => true], ['username' => 'ASC']);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelEntries[2]['id'], $user->getId());
        self::assertSame($modelEntries[2]['username'], $user->getUsername());
        self::assertSame($modelEntries[2]['password'], $user->getPassword());
        self::assertSame($modelEntries[2]['active'], $user->isActive());
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

        $users = $repo->findBy(
            ['password' => 'verysecurepassword'],
            ['username' => 'DESC'],
            1,
            1
        );

        self::assertCount(1, $users);

        $user = reset($users);

        self::assertInstanceOf(User::class, $user);

        self::assertSame($modelEntries[1]['id'], $user->getId());
        self::assertSame($modelEntries[1]['username'], $user->getUsername());
        self::assertSame($modelEntries[1]['password'], $user->getPassword());
        self::assertSame($modelEntries[1]['active'], $user->isActive());
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
        $user = $repo->find($modelEntries[0]['id']);

        self::assertInstanceOf(User::class, $user);

        $repo->remove($user);

        /** @var User $user */
        $user = $repo->find($modelEntries[0]['id']);

        self::assertNull($user);
    }
}
