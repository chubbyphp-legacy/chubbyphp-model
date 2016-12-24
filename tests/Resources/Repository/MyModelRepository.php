<?php

declare(strict_types=1);

namespace MyProject\Repository;

use Chubbyphp\Model\Collection\LazyModelCollection;
use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\Reference\LazyModelReference;
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
            $this->resolver, MyEmbeddedModel::class, $modelEntry['oneToOneId']
        );

        $modelEntry['oneToMany'] = new LazyModelCollection(
            $this->resolver, MyEmbeddedModel::class, ['modelId' => $modelEntry['id']], ['name' => 'ASC']
        );

        return MyModel::fromPersistence($modelEntry);
    }
}
