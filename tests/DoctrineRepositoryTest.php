<?php

namespace Chubbyphp\Tests\Model;

use Chubbyphp\Model\AbstractDoctrineRepository;
use Chubbyphp\Tests\Model\Resources\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;

final class DoctrineRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFindNotFound()
    {
        $queryBuilder = $this->getQueryBuilder([
            $this->getStatement(\PDO::FETCH_ASSOC, false),
        ]);

        $repository = $this->getDoctrineRepository(
            $this->getConnection([$queryBuilder]),
            User::class,
            'users'
        );

        self::assertNull($repository->find('id1'));

        self::assertEquals(
            array(
                'select' => array(
                    0 => array(
                        0 => '*',
                    ),
                ),
                'from' => array(
                    0 => array(
                        0 => 'users',
                        1 => null,
                    ),
                ),
                'where' => array(
                    0 => array(
                        0 => array(
                            'method' => 'eq',
                            'arguments' => array(
                                0 => 'id',
                                1 => ':id',
                            ),
                        ),
                    ),
                ),
                'setParameter' => array(
                    0 => array(
                        0 => 'id',
                        1 => 'id1',
                        2 => null,
                    ),
                ),
            ),
            $queryBuilder->__calls
        );
    }

    public function testFindFound()
    {
        $queryBuilder = $this->getQueryBuilder([
            $this->getStatement(\PDO::FETCH_ASSOC, [
                'id' => 'id1',
                'username' => 'username',
                'password' => 'password',
                'active' => true,
            ]),
        ]);

        $repository = $this->getDoctrineRepository(
            $this->getConnection([$queryBuilder]),
            User::class,
            'users'
        );

        /** @var User $user */
        $user = $repository->find('id1');

        self::assertInstanceOf(User::class, $user);

        self::assertSame('id1', $user->getId());
        self::assertSame('username', $user->getUsername());
        self::assertSame('password', $user->getPassword());
        self::assertTrue($user->isActive());

        self::assertEquals(
            array(
                'select' => array(
                    0 => array(
                        0 => '*',
                    ),
                ),
                'from' => array(
                    0 => array(
                        0 => 'users',
                        1 => null,
                    ),
                ),
                'where' => array(
                    0 => array(
                        0 => array(
                            'method' => 'eq',
                            'arguments' => array(
                                0 => 'id',
                                1 => ':id',
                            ),
                        ),
                    ),
                ),
                'setParameter' => array(
                    0 => array(
                        0 => 'id',
                        1 => 'id1',
                        2 => null,
                    ),
                ),
            ),
            $queryBuilder->__calls
        );
    }

    public function testFindOneByNotFound()
    {
        $queryBuilder = $this->getQueryBuilder([
            $this->getStatement(\PDO::FETCH_ASSOC, false),
        ]);

        $repository = $this->getDoctrineRepository(
            $this->getConnection([$queryBuilder]),
            User::class,
            'users'
        );

        self::assertNull($repository->findOneBy(['username' => 'user1']));

        self::assertEquals(
            array(
                'select' => array(
                    0 => array(
                        0 => '*',
                    ),
                ),
                'from' => array(
                    0 => array(
                        0 => 'users',
                        1 => null,
                    ),
                ),
                'setMaxResults' => array(
                    0 => array(
                        0 => 1,
                    ),
                ),
                'andWhere' => array(
                    0 => array(
                        0 => array(
                            'method' => 'eq',
                            'arguments' => array(
                                0 => 'username',
                                1 => ':username',
                            ),
                        ),
                    ),
                ),
                'setParameter' => array(
                    0 => array(
                        0 => 'username',
                        1 => 'user1',
                        2 => null,
                    ),
                ),
            ),
            $queryBuilder->__calls
        );
    }

    public function testFindOneByFound()
    {
        $queryBuilder = $this->getQueryBuilder([
            $this->getStatement(\PDO::FETCH_ASSOC, [
                'id' => 'id1',
                'username' => 'user1',
                'password' => 'password',
                'active' => true,
            ]),
        ]);

        $repository = $this->getDoctrineRepository(
            $this->getConnection([$queryBuilder]),
            User::class,
            'users'
        );

        /** @var User $user */
        $user = $repository->findOneBy(['username' => 'user1']);

        self::assertInstanceOf(User::class, $user);

        self::assertSame('id1', $user->getId());
        self::assertSame('user1', $user->getUsername());
        self::assertSame('password', $user->getPassword());
        self::assertTrue($user->isActive());

        self::assertEquals(
            array(
                'select' => array(
                    0 => array(
                        0 => '*',
                    ),
                ),
                'from' => array(
                    0 => array(
                        0 => 'users',
                        1 => null,
                    ),
                ),
                'setMaxResults' => array(
                    0 => array(
                        0 => 1,
                    ),
                ),
                'andWhere' => array(
                    0 => array(
                        0 => array(
                            'method' => 'eq',
                            'arguments' => array(
                                0 => 'username',
                                1 => ':username',
                            ),
                        ),
                    ),
                ),
                'setParameter' => array(
                    0 => array(
                        0 => 'username',
                        1 => 'user1',
                        2 => null,
                    ),
                ),
            ),
            $queryBuilder->__calls
        );
    }

    public function testFindByNotFound()
    {
        $queryBuilder = $this->getQueryBuilder([
            $this->getStatement(\PDO::FETCH_ASSOC, []),
        ]);

        $repository = $this->getDoctrineRepository(
            $this->getConnection([$queryBuilder]),
            User::class,
            'users'
        );

        self::assertSame([], $repository->findBy(['active' => true]));

        self::assertEquals(
            array(
                'select' => array(
                    0 => array(
                        0 => '*',
                    ),
                ),
                'from' => array(
                    0 => array(
                        0 => 'users',
                        1 => null,
                    ),
                ),
                'andWhere' => array(
                    0 => array(
                        0 => array(
                            'method' => 'eq',
                            'arguments' => array(
                                0 => 'active',
                                1 => ':active',
                            ),
                        ),
                    ),
                ),
                'setParameter' => array(
                    0 => array(
                        0 => 'active',
                        1 => true,
                        2 => null,
                    ),
                ),
            ),
            $queryBuilder->__calls
        );
    }

    public function testFindByFound()
    {
        $queryBuilder = $this->getQueryBuilder([
            $this->getStatement(\PDO::FETCH_ASSOC, [
                [
                    'id' => 'id1',
                    'username' => 'user1',
                    'password' => 'password',
                    'active' => true,
                ],
                [
                    'id' => 'id2',
                    'username' => 'user2',
                    'password' => 'password',
                    'active' => true,
                ],
            ]),
        ]);

        $repository = $this->getDoctrineRepository(
            $this->getConnection([$queryBuilder]),
            User::class,
            'users'
        );

        $users = $repository->findBy(['active' => true]);

        self::assertCount(2, $users);

        self::assertInstanceOf(User::class, $users[0]);

        self::assertSame('id1', $users[0]->getId());
        self::assertSame('user1', $users[0]->getUsername());
        self::assertSame('password', $users[0]->getPassword());
        self::assertTrue($users[0]->isActive());

        self::assertInstanceOf(User::class, $users[1]);

        self::assertSame('id2', $users[1]->getId());
        self::assertSame('user2', $users[1]->getUsername());
        self::assertSame('password', $users[1]->getPassword());
        self::assertTrue($users[1]->isActive());

        self::assertEquals(
            array(
                'select' => array(
                    0 => array(
                        0 => '*',
                    ),
                ),
                'from' => array(
                    0 => array(
                        0 => 'users',
                        1 => null,
                    ),
                ),
                'andWhere' => array(
                    0 => array(
                        0 => array(
                            'method' => 'eq',
                            'arguments' => array(
                                0 => 'active',
                                1 => ':active',
                            ),
                        ),
                    ),
                ),
                'setParameter' => array(
                    0 => array(
                        0 => 'active',
                        1 => true,
                        2 => null,
                    ),
                ),
            ),
            $queryBuilder->__calls
        );
    }

    /**
     * @param Connection $connection
     * @param string     $modelClass
     * @param string     $table
     *
     * @return AbstractDoctrineRepository
     */
    private function getDoctrineRepository(
        Connection $connection,
        string $modelClass,
        string $table
    ): AbstractDoctrineRepository {
        /** @var AbstractDoctrineRepository|\PHPUnit_Framework_MockObject_MockObject $repository */
        $repository = $this
            ->getMockBuilder(AbstractDoctrineRepository::class)
            ->setConstructorArgs([$connection])
            ->setMethods(['getModelClass', 'getTable'])
            ->getMockForAbstractClass();

        $repository->expects(self::any())->method('getModelClass')->willReturn($modelClass);
        $repository->expects(self::any())->method('getTable')->willReturn($table);

        return $repository;
    }

    /**
     * @param QueryBuilder[]|array $queryBuilderStack
     *
     * @return Connection
     */
    private function getConnection(array $queryBuilderStack): Connection
    {
        /* @var Connection|\PHPUnit_Framework_MockObject_MockObject $connection */
        $repository = $this
            ->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['createQueryBuilder'])
            ->getMockForAbstractClass();

        $queryBuilderCounter = 0;

        $repository
            ->expects(self::any())
            ->method('createQueryBuilder')
            ->willReturnCallback(function () use (&$queryBuilderStack, &$queryBuilderCounter) {
                ++$queryBuilderCounter;

                $queryBuilder = array_shift($queryBuilderStack);

                self::assertNotNull($queryBuilder,
                    sprintf(
                        'createQueryBuilder failed, cause there was no data within $queryBuilderStack at call %d',
                        $queryBuilderCounter
                    )
                );

                return $queryBuilder;
            });

        return $repository;
    }

    /**
     * @param array $executeStack
     *
     * @return QueryBuilder
     */
    private function getQueryBuilder(array $executeStack): QueryBuilder
    {
        $modifiers = [
            'setParameter',
            'setParameters',
            'setFirstResult',
            'setMaxResults',
            'add',
            'select',
            'addSelect',
            'delete',
            'update',
            'insert',
            'from',
            'innerJoin',
            'leftJoin',
            'rightJoin',
            'set',
            'where',
            'andWhere',
            'orWhere',
            'groupBy',
            'addGroupBy',
            'setValue',
            'values',
            'having',
            'andHaving',
            'orHaving',
            'orderBy',
            'addOrderBy',
            'resetQueryParts',
            'resetQueryPart',
        ];

        /** @var QueryBuilder|\PHPUnit_Framework_MockObject_MockObject $queryBuilder */
        $queryBuilder = $this
            ->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(array_merge($modifiers, ['expr', 'execute']))
            ->getMockForAbstractClass();

        $queryBuilder->__calls = [];

        foreach ($modifiers as $modifier) {
            $queryBuilder
                ->expects(self::any())
                ->method($modifier)
                ->willReturnCallback(function () use ($queryBuilder, $modifier) {
                    if (!isset($queryBuilder->__calls[$modifier])) {
                        $queryBuilder->__calls[$modifier] = [];
                    }

                    $queryBuilder->__calls[$modifier][] = func_get_args();

                    return $queryBuilder;
                });
        }

        $queryBuilder
            ->expects(self::any())
            ->method('expr')
            ->willReturnCallback(function () {
                return $this->getExpressionBuilder();
            });

        $executeStackCounter = 0;

        $queryBuilder
            ->expects(self::any())
            ->method('execute')
            ->willReturnCallback(function () use ($queryBuilder, &$executeStack, &$executeStackCounter) {
                ++$executeStackCounter;

                $execute = array_shift($executeStack);

                self::assertNotNull($execute,
                    sprintf(
                        'execute failed, cause there was no data within $executeStack at call %d',
                        $executeStackCounter
                    )
                );

                return $execute;
            });

        return $queryBuilder;
    }

    /**
     * @return ExpressionBuilder
     */
    private function getExpressionBuilder(): ExpressionBuilder
    {
        $comparsions = [
            'andX',
            'orX',
            'comparison',
            'eq',
            'neq',
            'lt',
            'lte',
            'gt',
            'gte',
            'isNull',
            'isNotNull',
            'like',
            'notLike',
            'in',
            'notIn',
            'literal',
        ];

        /** @var ExpressionBuilder|\PHPUnit_Framework_MockObject_MockObject $expr */
        $expr = $this
            ->getMockBuilder(ExpressionBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods($comparsions)
            ->getMockForAbstractClass();

        foreach ($comparsions as $comparsion) {
            $expr
                ->expects(self::any())
                ->method($comparsion)
                ->willReturnCallback(function () use ($comparsion) {
                    return ['method' => $comparsion, 'arguments' => func_get_args()];
                });
        }

        return $expr;
    }

    /**
     * @param int   $checkType
     * @param mixed $data
     *
     * @return Statement
     */
    private function getStatement(int $checkType, $data): Statement
    {
        /** @var Statement|\PHPUnit_Framework_MockObject_MockObject $stmt */
        $stmt = $this
            ->getMockBuilder(Statement::class)
            ->setMethods(['fetch', 'fetchAll'])
            ->getMockForAbstractClass();

        $stmt
            ->expects(self::any())
            ->method('fetch')
            ->willReturnCallback(function (int $type) use ($checkType, $data) {
                self::assertSame($checkType, $type);

                return $data;
            });

        $stmt
            ->expects(self::any())
            ->method('fetchAll')
            ->willReturnCallback(function (int $type) use ($checkType, $data) {
                self::assertSame($checkType, $type);

                return $data;
            });

        return $stmt;
    }
}
