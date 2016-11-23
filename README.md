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

 * chubbyphp/chubbyphp-model-doctrine-dbal: ~1.0@dev
 * container-interop/container-interop: ~1.1

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-model][1].

## Usage

### Model

#### Sample User

```{.php}
<?php

namespace MyProject\Model;

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
    private $username;

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
    public static function fromPersistence(array $data): ModelInterface
    {
        $object = new self($data['id']);
        $object->username = $data['username'];
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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
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
    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password,
            'active' => $this->active,
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'active' => $this->active,
        ];
    }
}
```

### Repository

#### Sample UserRepository

```{.php}
<?php

namespace MyProject\Repository;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\RepositoryInterface;

final class UserRepository implements RepositoryInterface
{
    /**
     * @var array[]
     */
    private $modelEntries;

    /**
     * @param array $modelEntries
     */
    public function __construct(array $modelEntries = [])
    {
        $this->modelEntries = [];
        foreach ($modelEntries as $modelEntry) {
            $this->modelEntries[$modelEntry['id']] = $modelEntry;
        }
    }

    /**
     * @return string
     */
    public static function getModelClass(): string
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
        if (!isset($this->modelEntries[$id])) {
            return null;
        }

        /** @var User $modelClass */
        $modelClass = self::getModelClass();

        return $modelClass::fromPersistence($this->modelEntries[$id]);
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

        return reset($models);
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        /** @var User $modelClass */
        $modelClass = self::getModelClass();

        $models = [];
        foreach ($this->modelEntries as $modelEntry) {
            foreach ($criteria as $key => $value) {
                if ($modelEntry[$key] !== $value) {
                    continue 2;
                }
            }

            $models[] = $modelClass::fromPersistence($modelEntry);
        }

        if (null !== $orderBy) {
            usort($models, function (ModelInterface $a, ModelInterface $b) use ($orderBy) {
                foreach ($orderBy as $key => $value) {
                    $propertyReflection = new \ReflectionProperty(get_class($a), $key);
                    $propertyReflection->setAccessible(true);
                    $sorting = strcmp($propertyReflection->getValue($a), $propertyReflection->getValue($b));
                    if ($value === 'DESC') {
                        $sorting = $sorting * -1;
                    }

                    if (0 !== $sorting) {
                        return $sorting;
                    }
                }

                return 0;
            });
        }

        if (null !== $limit && null !== $offset) {
            return array_slice($models, $offset, $limit);
        }

        if (null !== $limit) {
            return array_slice($models, 0, $limit);
        }

        return $models;
    }

    /**
     * @param ModelInterface $model
     *
     * @thentries \Exception
     */
    public function persist(ModelInterface $model)
    {
        $this->modelEntries[$model->getId()] = $model->toPersistence();
    }

    /**
     * @param ModelInterface $model
     *
     * @thentries \Exception
     */
    public function remove(ModelInterface $model)
    {
        $id = $model->getId();
        if (!isset($this->modelEntries[$id])) {
            return;
        }

        unset($this->modelEntries[$id]);
    }
}
```

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-model

## Copyright

Dominik Zogg 2016
