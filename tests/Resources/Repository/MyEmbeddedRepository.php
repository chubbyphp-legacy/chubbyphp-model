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
