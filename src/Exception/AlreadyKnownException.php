<?php

namespace Chubbyphp\Model\Exception;

class AlreadyKnownException extends \RuntimeException
{
    /**
     * @param string $modelClass
     * @param string $id
     *
     * @return self
     */
    public static function create(string $modelClass, string $id)
    {
        return new self(sprintf('Already known model of class %s with id %s', $modelClass, $id));
    }
}
