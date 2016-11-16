<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Doctrine\DBAL;

class MissingRepositoryException extends \RuntimeException
{
    /**
     * @param string $modelClass
     *
     * @return MissingRepositoryException
     */
    public static function create(string $modelClass): self
    {
        return new self(sprintf('Missing repository for model "%s"', $modelClass));
    }
}
