<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Model\Exception\NotUniqueException;
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
        $modelRows = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'user1d',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $repo = new UserRepository($modelRows);

        self::assertNull($repo->find('unknown'));

        /** @var User $user */
        $user = $repo->find($modelRows[0]['id']);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelRows[0]['id'], $user->getId());
        self::assertSame($modelRows[0]['username'], $user->getUsername());
        self::assertSame($modelRows[0]['password'], $user->getPassword());
        self::assertSame($modelRows[0]['active'], $user->isActive());
    }

    public function testFindBy()
    {
        $modelRows = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'user1d',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname@domain.tld',
                'password' => 'verysecurepassword',
                'active' => false,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $repo = new UserRepository($modelRows);

        $activeUsers = $repo->findBy(['active' => true]);

        self::assertCount(2, $activeUsers);

        /** @var User $activeUser */
        $activeUser = array_shift($activeUsers);

        self::assertInstanceOf(User::class, $activeUser);
        self::assertSame($modelRows[0]['id'], $activeUser->getId());
        self::assertSame($modelRows[0]['username'], $activeUser->getUsername());
        self::assertSame($modelRows[0]['password'], $activeUser->getPassword());
        self::assertSame($modelRows[0]['active'], $activeUser->isActive());

        /** @var User $activeUser */
        $activeUser = array_shift($activeUsers);

        self::assertInstanceOf(User::class, $activeUser);
        self::assertSame($modelRows[2]['id'], $activeUser->getId());
        self::assertSame($modelRows[2]['username'], $activeUser->getUsername());
        self::assertSame($modelRows[2]['password'], $activeUser->getPassword());
        self::assertSame($modelRows[2]['active'], $activeUser->isActive());

        $inactiveUsers = $repo->findBy(['active' => false]);

        self::assertCount(1, $inactiveUsers);

        /** @var User $inactiveUser */
        $inactiveUser = array_shift($inactiveUsers);

        self::assertInstanceOf(User::class, $inactiveUser);
        self::assertSame($modelRows[1]['id'], $inactiveUser->getId());
        self::assertSame($modelRows[1]['username'], $inactiveUser->getUsername());
        self::assertSame($modelRows[1]['password'], $inactiveUser->getPassword());
        self::assertSame($modelRows[1]['active'], $inactiveUser->isActive());
    }

    public function testFindOneBy()
    {
        $modelRows = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'user1d',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname@domain.tld',
                'password' => 'verysecurepassword',
                'active' => false,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $repo = new UserRepository($modelRows);

        /** @var User $user */
        $user = $repo->findOneBy(['username' => 'user1d']);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($modelRows[0]['id'], $user->getId());
        self::assertSame($modelRows[0]['username'], $user->getUsername());
        self::assertSame($modelRows[0]['password'], $user->getPassword());
        self::assertSame($modelRows[0]['active'], $user->isActive());
    }

    public function testFindOneByNotUniqueExceptsException()
    {
        self::expectException(NotUniqueException::class);
        self::expectExceptionMessage(
            'There are 2 models of class '.User::class.' for criteria username: nickname@domain.tld'
        );

        $modelRows = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'user1d',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname@domain.tld',
                'password' => 'verysecurepassword',
                'active' => false,
            ],
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'nickname@domain.tld',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $repo = new UserRepository($modelRows);
        $repo->findOneBy(['username' => 'nickname@domain.tld']);
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
        $modelRows = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'user1d',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $repo = new UserRepository($modelRows);

        /** @var User $user */
        $user = $repo->find($modelRows[0]['id']);

        self::assertInstanceOf(User::class, $user);

        $user->setUsername('nickname@domain.tld');

        $repo->persist($user);

        /** @var User $user */
        $user = $repo->find($modelRows[0]['id']);

        self::assertInstanceOf(User::class, $user);

        self::assertSame('nickname@domain.tld', $user->getUsername());
    }

    public function testDelete()
    {
        $modelRows = [
            [
                'id' => (string) Uuid::uuid4(),
                'username' => 'user1d',
                'password' => 'verysecurepassword',
                'active' => true,
            ],
        ];

        $repo = new UserRepository($modelRows);

        /** @var User $user */
        $user = $repo->find($modelRows[0]['id']);

        $repo->delete($user);
    }
}
