<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

use Chubbyphp\Model\Cache\ModelCacheInterface;
use Chubbyphp\Model\Cache\NullModelCache;
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
     * @var ModelCacheInterface
     */
    private $cache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Connection               $connection
     * @param ModelCacheInterface|null $cache
     * @param LoggerInterface|null     $logger
     */
    public function __construct(
        Connection $connection,
        ModelCacheInterface $cache = null,
        LoggerInterface $logger = null
    ) {
        $this->connection = $connection;
        $this->cache = $cache ?? new NullModelCache();
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

        $this->logger->info('model: find model {model} with id {id}', ['model' => $modelClass, 'id' => $id]);

        if ($this->cache->has($id)) {
            return $this->cache->get($id);
        }

        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from($this->getTable())->where($qb->expr()->eq('id', ':id'))->setParameter('id', $id);

        $row = $qb->execute()->fetch(\PDO::FETCH_ASSOC);
        if (false === $row) {
            $this->logger->warning(
                'model: model {model} with id {id} not found',
                ['model' => $modelClass, 'id' => $id]
            );

            return null;
        }

        $model = $modelClass::fromRow($row);

        $this->cache->set($model);

        return $model;
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
            'model: find model {model} with criteria {criteria}',
            ['model' => $modelClass, 'criteria' => $criteria]
        );

        $qb = $this->getFindByQueryBuilder($criteria)->setMaxResults(1);

        $row = $qb->execute()->fetch(\PDO::FETCH_ASSOC);
        if (false === $row) {
            $this->logger->warning(
                'model: model {model} with criteria {criteria} not found',
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
            'model: find model {model} with criteria {criteria}',
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
            'model: insert model {model} with id {id}',
            ['model' => get_class($model), 'id' => $model->getId()]
        );

        $this->connection->insert($this->getTable(), $model->toRow());

        $this->cache->set($model);
    }

    /**
     * @param ModelInterface $model
     */
    public function update(ModelInterface $model)
    {
        $this->logger->info(
            'model: update model {model} with id {id}',
            ['model' => get_class($model), 'id' => $model->getId()]
        );

        $this->connection->update($this->getTable(), $model->toRow(), ['id' => $model->getId()]);

        $this->cache->set($model);
    }

    /**
     * @param ModelInterface $model
     */
    public function delete(ModelInterface $model)
    {
        $this->logger->info(
            'model: delete model {model} with id {id}',
            ['model' => get_class($model), 'id' => $model->getId()]
        );

        $this->connection->delete($this->getTable(), ['id' => $model->getId()]);

        $this->cache->remove($model->getId());
    }

    /**
     * @return string
     */
    abstract protected function getTable(): string;
}
