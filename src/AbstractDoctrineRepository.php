<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractDoctrineRepository implements RepositoryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection, LoggerInterface $logger = null)
    {
        $this->connection = $connection;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param string $id
     *
     * @return ModelInterface|null
     */
    public function find(string $id)
    {
        /** @var ModelInterface $modelClass */
        $modelClass = $this->getModelClass();

        $this->logger->info('Find model {model} with id {id}', ['model' => $modelClass, 'id' => $id]);

        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from($this->getTable())->where($qb->expr()->eq('id', ':id'))->setParameter('id', $id);

        $row = $qb->execute()->fetch(\PDO::FETCH_ASSOC);
        if (false === $row) {
            $this->logger->warning('Model {model} with id {id} not found', ['model' => $modelClass, 'id' => $id]);

            return null;
        }

        return $modelClass::fromRow($row);
    }

    /**
     * @param array $criteria
     *
     * @return null|ModelInterface
     */
    public function findOneBy(array $criteria = [])
    {
        /** @var ModelInterface $modelClass */
        $modelClass = $this->getModelClass();

        $this->logger->info(
            'Find model {model} by criteria {criteria}',
            ['model' => $modelClass, 'criteria' => $criteria]
        );

        $qb = $this->getFindByQueryBuilder($criteria)->setMaxResults(1);

        $row = $qb->execute()->fetch(\PDO::FETCH_ASSOC);
        if (false === $row) {
            $this->logger->warning(
                'Model {model} by criteria {criteria} not found',
                ['model' => $modelClass, 'criteria' => $criteria]
            );

            return null;
        }

        return $modelClass::fromRow($row);
    }

    /**
     * @param array $criteria
     *
     * @return ModelInterface[]|array
     */
    public function findBy(array $criteria = []): array
    {
        /** @var ModelInterface $modelClass */
        $modelClass = $this->getModelClass();

        $this->logger->info(
            'Find model {model} by criteria {criteria}',
            ['model' => $modelClass, 'criteria' => $criteria]
        );

        $rows = $this->getFindByQueryBuilder($criteria)->execute()->fetchAll(\PDO::FETCH_ASSOC);

        if ([] === $rows) {
            return [];
        }

        $models = [];
        foreach ($rows as $row) {
            $models[] = $modelClass::fromRow($row);
        }

        return $models;
    }

    /**
     * @param array $criteria
     *
     * @return QueryBuilder
     */
    private function getFindByQueryBuilder(array $criteria = []): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from($this->getTable());

        foreach ($criteria as $field => $value) {
            $qb->andWhere($qb->expr()->eq($field, ':'.$field));
            $qb->setParameter($field, $value);
        }

        return $qb;
    }

    /**
     * @param ModelInterface $model
     */
    public function insert(ModelInterface $model)
    {
        $this->logger->info(
            'Insert model {model} with id {id}',
            ['model' => get_class($model), 'id' => $model->getId()]
        );

        $this->connection->insert($this->getTable(), $model->toRow());
    }

    /**
     * @param ModelInterface $model
     */
    public function update(ModelInterface $model)
    {
        $this->logger->info(
            'Update model {model} with id {id}',
            ['model' => get_class($model), 'id' => $model->getId()]
        );

        $this->connection->update($this->getTable(), $model->toRow(), ['id' => $model->getId()]);
    }

    /**
     * @param ModelInterface $model
     */
    public function delete(ModelInterface $model)
    {
        $this->logger->info(
            'Delete model {model} with id {id}',
            ['model' => get_class($model), 'id' => $model->getId()]
        );

        $this->connection->delete($this->getTable(), ['id' => $model->getId()]);
    }

    /**
     * @return string
     */
    abstract protected function getTable(): string;
}
