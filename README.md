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
use MyProject\Model\MyModel;

$resolver = ...

$collection = new LazyModelCollection($resolver->lazyFindBy(....));

$model = new MyModel();
$model->setName('name1');
$model->setCategory('category1');

$collection->addModel($model);

```

#### ModelCollection

```{.php}
<?php

use Chubbyphp\Model\Collection\ModelCollection;
use MyProject\Model\MyModel;

$collection = new ModelCollection([]);

$model = new MyModel();
$model->setName('name1');
$model->setCategory('category1');

$collection->addModel($model);
```

### Reference

#### LazyModelReference

```{.php}
<?php

use Chubbyphp\Model\Reference\LazyModelReference;
use MyProject\Model\MyModel;

$resolver = ...

$reference = new LazyModelReference($resolver->lazyFindOneBy(....));

$model = new MyModel();
$model->setName('name1');
$model->setCategory('category1');

$reference->setModel($model);

```

#### ModelReference

```{.php}
<?php

use Chubbyphp\Model\Reference\ModelReference;
use MyProject\Model\MyModel;

$reference = new ModelReference([]);

$model = new MyModel();
$model->setName('name1');
$model->setCategory('category1');

$reference->setModel($model);
```

### Model

#### Sample MyModel

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
use MyProject\Model\MyModel;
use MyProject\Repository\MyRepository;

$container = ...

$resolver = new Resolver($container, [MyRepository::class]);
$resolver->find(MyModel::class, 5);
```

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-model

## Copyright

Dominik Zogg 2016
