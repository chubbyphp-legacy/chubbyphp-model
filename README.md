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

$collection = new ModelCollection();

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

$reference = new ModelReference();

$model = new MyModel();
$model->setName('name1');
$model->setCategory('category1');

$reference->setModel($model);
```

### Model

#### MyModel

```{.php}
<?php

declare(strict_types=1);

namespace MyProject\Model;

use Chubbyphp\Model\Collection\ModelCollection;
use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\Reference\ModelReference;
use Ramsey\Uuid\Uuid;

final class MyModel implements ModelInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $category;

    /**
     * @var ModelReference
     */
    private $oneToOne;

    /**
     * @var ModelCollection
     */
    private $oneToMany;

    /**
     * @param string|null $id
     * @return MyModel
     */
    public static function create(string $id = null): MyModel
    {
        $myModel = new self;
        $myModel->id = $id ?? (string) Uuid::uuid4();
        $myModel->oneToOne = new ModelReference();
        $myModel->oneToMany = new ModelCollection();

        return $myModel;
    }

    private function __construct() {}

    /**
     * @param array $data
     *
     * @return ModelInterface
     */
    public static function fromPersistence(array $data): ModelInterface
    {
        $model = new self;
        $model->id = $data['id'];
        $model->name = $data['name'];
        $model->category = $data['category'];
        $model->oneToOne = $data['oneToOne'];
        $model->oneToMany = $data['oneToMany'];

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
     * @param string $name
     * @return self
     */
    public function setName(string $name): MyModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $category
     * @return self
     */
    public function setCategory(string $category): MyModel
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param MyEmbeddedModel|null $oneToOne
     * @return self
     */
    public function setOneToOne(MyEmbeddedModel $oneToOne = null): MyModel
    {
        $this->oneToOne->setModel($oneToOne);

        return $this;
    }

    /**
     * @return MyEmbeddedModel|ModelInterface|null
     */
    public function getOneToOne()
    {
        return $this->oneToOne->getModel();
    }

    /**
     * @param MyEmbeddedModel[]|array $oneToMany
     * @return $this
     */
    public function setOneToMany(array $oneToMany)
    {
        $this->oneToMany->setModels($oneToMany);

        return $this;
    }

    /**
     * @return MyEmbeddedModel[]|ModelInterface[]|array
     */
    public function getOneToMany()
    {
        return $this->oneToMany->getModels();
    }

    /**
     * @return array
     */
    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'oneToOne' => $this->oneToOne,
            'oneToMany' => $this->oneToMany
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'oneToOne' => $this->oneToOne->jsonSerialize(),
            'oneToMany' => $this->oneToMany->jsonSerialize()
        ];
    }
}
```

#### MyEmbeddedModel

```{.php}
<?php

declare(strict_types=1);

namespace MyProject\Model;

use Chubbyphp\Model\ModelInterface;
use Ramsey\Uuid\Uuid;

final class MyEmbeddedModel implements ModelInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $modelId;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $modelId
     * @param string|null $id
     * @return MyEmbeddedModel
     */
    public function create(string $modelId, string $id = null): MyEmbeddedModel
    {
        $myEmbeddedModel = new self;
        $myEmbeddedModel->id = $id ?? (string) Uuid::uuid4();
        $myEmbeddedModel->modelId = $modelId;

        return $myEmbeddedModel;
    }

    private function __construct() {}

    /**
     * @param array $data
     *
     * @return MyEmbeddedModel|ModelInterface
     */
    public static function fromPersistence(array $data): ModelInterface
    {
        $model = new self;
        $model->id = $data['id'];
        $model->modelId = $data['modelId'];
        $model->name = $data['name'];

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
     * @param string $name
     * @return MyEmbeddedModel
     */
    public function setName(string $name): MyEmbeddedModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            'modelId' => $this->modelId,
            'name' => $this->name
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
```

### Repository

#### MyModelRepository

```{.php}
<?php

declare(strict_types=1);

namespace MyProject\Repository;

use Chubbyphp\Model\Collection\LazyModelCollection;
use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\Reference\LazyModelReference;
use Chubbyphp\Model\Reference\ModelReference;
use MyProject\Model\MyEmbeddedModel;
use MyProject\Model\MyModel;

final class MyModelRepository extends AbstractRepository
{
    /**
     * @param string $modelClass
     * @return bool
     */
    public function isResponsible(string $modelClass): bool
    {
        return MyModel::class === $modelClass;
    }

    /**
     * @return array
     */
    public function findByMagicMethod(): array
    {
        return func_get_args();
    }

    /**
     * @param array $modelEntry
     * @return MyModel|ModelInterface
     */
    protected function fromPersistence(array $modelEntry): ModelInterface
    {
        $modelEntry['oneToOne'] = new LazyModelReference(
            $this->resolver->lazyFind(MyEmbeddedModel::class, $modelEntry['oneToOneId'])
        );

        $modelEntry['oneToMany'] = new LazyModelCollection(
            $this->resolver->lazyFindBy(MyEmbeddedModel::class, ['modelId' => $modelEntry['id']])
        );

        return MyModel::fromPersistence($modelEntry);
    }
}
```

#### MyEmbeddedRepository

```{.php}
<?php

declare(strict_types=1);

namespace MyProject\Repository;

use Chubbyphp\Model\ModelInterface;
use MyProject\Model\MyEmbeddedModel;

final class MyEmbeddedRepository extends AbstractRepository
{
    /**
     * @param string $modelClass
     * @return bool
     */
    public function isResponsible(string $modelClass): bool
    {
        return MyEmbeddedModel::class === $modelClass;
    }

    /**
     * @param array $modelEntry
     * @return MyEmbeddedModel|ModelInterface
     */
    protected function fromPersistence(array $modelEntry): ModelInterface
    {
        return MyEmbeddedModel::fromPersistence($modelEntry);
    }
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
