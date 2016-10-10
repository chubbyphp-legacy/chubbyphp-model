<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Exception;

class UnknownException extends \RuntimeException
{
    /**
     * @param string $modelClass
     * @param string $id
     *
     * @return self
     */
    public static function create(string $modelClass, string $id)
    {
        return new self(sprintf('Unknown model of class %s with id %s', $modelClass, $id));
    }
}
