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

### Collection

#### LazyModelCollection

```{.php}
<?php

use Chubbyphp\Model\Collection\LazyModelCollection;
use MyProject\Model\User;

$resolver = ...

$collection = new LazyModelCollection($resolver->lazyFindBy(....));

$user = new User();
$user->setUsername('username1');
$user->setPassword('password');
$user->setActive(true);

$collection->addModel($user);

```

#### ModelCollection

```{.php}
<?php

use Chubbyphp\Model\Collection\ModelCollection;
use MyProject\Model\User;

$collection = new ModelCollection([]);

$user = new User();
$user->setUsername('username1');
$user->setPassword('password');
$user->setActive(true);

$collection->addModel($user);
```

### Model

#### Sample User

```{.php}
<?php

namespace MyProject\Model;

use Chubbyphp\Model\ModelInterface;
use Ramsey\Uuid\Uuid;

final class MyModel implements ModelInterface
{
    /**
     * @var string
     */
    private $id;

    ....

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
        $model = new self($data['id']);
        ...

        return $model;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            ...
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            ...
        ];
    }
}
```

### Repository

#### Sample MyRepository

```{.php}
<?php

namespace MyProject\Repository;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\RepositoryInterface;

final class MyRepository implements RepositoryInterface
{
   ...
}
```

### Resolver

```{.php}
<?php

use Chubbyphp\Model\Resolver;
use Interop\Container\ContainerInterface;
use MyProject\Model\User;
use MyProject\Repository\MyRepository;

$container = ...

$resolver = new Resolver($container, [MyRepository::class]);
$resolver->find(User::class, 5);
```

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-model

## Copyright

Dominik Zogg 2016
