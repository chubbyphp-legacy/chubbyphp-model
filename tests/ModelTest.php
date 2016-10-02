<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Tests\Model\Resources\User;
use Ramsey\Uuid\Uuid;

final class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function testGetId()
    {
        $id = (string) Uuid::uuid4();
        $user = new User($id);

        self::assertSame($id, $user->getId());
    }

    public function testFromRow()
    {
        $id = (string) Uuid::uuid4();
        $email = 'firstname.lastname@domain.tld';
        $password = 'verysecurepassword';
        $active = true;

        /** @var User $user */
        $user = User::fromRow(['id' => $id, 'email' => $email, 'password' => $password, 'active' => $active]);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($id, $user->getId());
        self::assertSame($email, $user->getEmail());
        self::assertSame($password, $user->getPassword());
        self::assertSame($active, $user->isActive());
    }

    public function testToRow()
    {
        $id = (string) Uuid::uuid4();
        $email = 'firstname.lastname@domain.tld';
        $password = 'verysecurepassword';
        $active = true;

        $user = new User($id);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setActive($active);

        self::assertSame(
            ['id' => $id, 'email' => $email, 'password' => $password, 'active' => $active],
            $user->toRow()
        );
    }
}
