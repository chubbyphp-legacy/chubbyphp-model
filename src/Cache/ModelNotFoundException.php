<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Cache;

class ModelNotFoundException extends \RuntimeException
{
    /**
     * @param string $id
     *
     * @return ModelNotFoundException
     */
    public static function fromId(string $id)
    {
        return new self(sprintf('Model with id %s not found within cache', $id));
    }
}
