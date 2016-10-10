<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Exception;

class NotUniqueException extends \RuntimeException
{
    /**
     * @param string $modelClass
     * @param int    $modelsCount
     * @param array  $criteria
     *
     * @return self
     */
    public static function create(string $modelClass, array $criteria, int $modelsCount)
    {
        return new self(
            sprintf(
                'There are %d models of class %s for criteria %s',
                $modelsCount,
                $modelClass,
                self::criteriaAsString($criteria)
            )
        );
    }

    /**
     * @param array $criteria
     *
     * @return string
     */
    private static function criteriaAsString(array $criteria): string
    {
        $criteriaAsString = '';
        foreach ($criteria as $key => $value) {
            $criteriaAsString .= $key.': '.$value.', ';
        }

        return substr($criteriaAsString, 0, -2);
    }
}
