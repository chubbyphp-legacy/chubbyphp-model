<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

trait ModelSortTrait
{
    /**
     * @param string $modelClass
     * @param array $models
     * @param array|null $orderBy
     * @return array
     */
    private function sort(string $modelClass, array $models, array $orderBy = null): array
    {
        if ([] === $models) {
            return [];
        }

        if (null === $orderBy) {
            return $models;
        }

        $reflections = [];
        foreach ($orderBy as $property => $sortingDirection) {
            $reflection = new \ReflectionProperty($modelClass, $property);
            $reflection->setAccessible(true);

            $reflections[$property] = $reflection;
        }

        usort($models, function (ModelInterface $a, ModelInterface $b) use ($reflections, $orderBy) {
            foreach ($orderBy as $property => $sortingDirection) {
                $reflection = $reflections[$property];
                $sorting = strcmp($reflection->getValue($a), $reflection->getValue($b));
                if ($sortingDirection === 'DESC') {
                    $sorting = $sorting * -1;
                }

                if (0 !== $sorting) {
                    return $sorting;
                }
            }

            return 0;
        });

        return $models;
    }
}
