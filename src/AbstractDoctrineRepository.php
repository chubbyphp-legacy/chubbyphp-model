<?php

namespace Chubbyphp\Model;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

abstract class AbstractDoctrineRepository implements RepositoryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $id
     *
     * @return ModelInterface|null
     */
    public function find(string $id)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from($this->getTable())->where($qb->expr()->eq('id', ':id'))->setParameter('id', $id);

        $row = $qb->execute()->fetch(\PDO::FETCH_ASSOC);
        if (false === $row) {
            return null;
        }

        /** @var ModelInterface $modelClass */
        $modelClass = $this->getModelClass();

        return $modelClass::fromRow($row);
    }

    /**
     * @param array $criteria
     *
     * @return null|ModelInterface
     */
    public function findOneBy(array $criteria = [])
    {
        $qb = $this->getFindByQueryBuilder($criteria)->setMaxResults(1);

        $row = $qb->execute()->fetch(\PDO::FETCH_ASSOC);
        if (false === $row) {
            return null;
        }

        /** @var ModelInterface $modelClass */
        $modelClass = $this->getModelClass();

        return $modelClass::fromRow($row);
    }

    /**
     * @param array $criteria
     *
     * @return ModelInterface[]|array
     */
    public function findBy(array $criteria = []): array
    {
        $rows = $this->getFindByQueryBuilder($criteria)->execute()->fetchAll(\PDO::FETCH_ASSOC);

        if ([] === $rows) {
            return [];
        }

        /** @var ModelInterface $modelClass */
        $modelClass = $this->getModelClass();

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
        $this->connection->insert($this->getTable(), $model->toRow());
    }

    /**
     * @param ModelInterface $model
     */
    public function update(ModelInterface $model)
    {
        $this->connection->update($this->getTable(), $model->toRow(), ['id' => $model->getId()]);
    }

    /**
     * @param ModelInterface $model
     */
    public function delete(ModelInterface $model)
    {
        $this->connection->delete($this->getTable(), ['id' => $model->getId()]);
    }

    /**
     * @return string
     */
    abstract protected function getTable(): string;
}
