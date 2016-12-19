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
