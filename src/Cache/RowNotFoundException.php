<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Cache;

class RowNotFoundException extends \RuntimeException
{
    /**
     * @param string $id
     *
     * @return RowNotFoundException
     */
    public static function fromId(string $id)
    {
        return new self(sprintf('Row with id %s not found within cache', $id));
    }
}
