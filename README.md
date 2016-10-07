# chubbyphp-model

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-model.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-model)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-model/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-model)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-model/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-model)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-model/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-model/?branch=master)

## Description

Model and repository made simple.

## Requirements

 * php: ~7.0

## Suggest

 * doctrine/dbal: ^2.5.5

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-model][1].

## Usage

### Model

#### Sample User

```{.php}
<?php

namespace Chubbyphp\Tests\Model\Resources;

use Chubbyphp\Model\ModelInterface;
use Ramsey\Uuid\Uuid;

final class User implements ModelInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var bool
     */
    private $active;

    /**
     * @param string|null $id
     */
    public function __construct(string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4();
    }

    /**
     * @param array $data
     *
     * @return ModelInterface
     */
    public static function fromRow(array $data): ModelInterface
    {
        $object = new self($data['id']);
        $object->email = $data['email'];
        $object->password = $data['password'];
        $object->active = $data['active'];

        return $object;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    /**
     * @return array
     */
    public function toRow(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            'active' => $this->active,
        ];
    }
}
```

### Repository

#### Sample UserRepository

```{.php}
final class UserRepository implements RepositoryInterface
{
    /**
     * @var array[]
     */
    private $modelRows;

    /**
     * @param array $modelRows
     */
    public function __construct(array $modelRows = [])
    {
        $this->modelRows = [];
        foreach ($modelRows as $modelRow) {
            $this->modelRows[$modelRow['id']] = $modelRow;
        }
    }

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return User::class;
    }

    /**
     * @param string $id
     *
     * @return ModelInterface|null
     */
    public function find(string $id)
    {
        if (!isset($this->modelRows[$id])) {
            return null;
        }

        /** @var User $modelClass */
        $modelClass = $this->getModelClass();

        return $modelClass::fromRow($this->modelRows[$id]);
    }

    /**
     * @param array $criteria
     *
     * @return array
     */
    public function findBy(array $criteria = []): array
    {
        /** @var User $modelClass */
        $modelClass = $this->getModelClass();

        $models = [];
        foreach ($this->modelRows as $modelRow) {
            foreach ($criteria as $key => $value) {
                if ($modelRow[$key] !== $value) {
                    continue 2;
                }
            }

            $models[] = $modelClass::fromRow($modelRow);
        }

        return $models;
    }

    /**
     * @param array $criteria
     *
     * @return ModelInterface|null
     */
    public function findOneBy(array $criteria = [])
    {
        $models = $this->findBy($criteria);

        if ([] === $models) {
            return null;
        }

        $modelsCount = count($models);

        if (1 === $modelsCount) {
            return reset($models);
        }

        throw NotUniqueException::create($this->getModelClass(), $criteria, $modelsCount);
    }

    /**
     * @param ModelInterface $model
     *
     * @throws \Exception
     */
    public function insert(ModelInterface $model)
    {
        $id = $model->getId();
        if (isset($this->modelRows[$id])) {
            throw AlreadyKnownException::create($this->getModelClass(), $id);
        }

        $this->modelRows[$model->getId()] = $model->toRow();
    }

    /**
     * @param ModelInterface $model
     *
     * @throws \Exception
     */
    public function update(ModelInterface $model)
    {
        $id = $model->getId();
        if (!isset($this->modelRows[$id])) {
            throw UnknownException::create($this->getModelClass(), $id);
        }

        $this->modelRows[$id] = $model->toRow();
    }

    /**
     * @param ModelInterface $model
     *
     * @throws \Exception
     */
    public function delete(ModelInterface $model)
    {
        $id = $model->getId();
        if (!isset($this->modelRows[$id])) {
            throw UnknownException::create($this->getModelClass(), $id);
        }

        unset($this->modelRows[$id]);
    }
}
```

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-model

## Copyright

Dominik Zogg 2016
