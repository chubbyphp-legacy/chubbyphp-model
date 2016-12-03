<?php

declare(strict_types=1);

namespace Chubbyphp\Model\StorageCache;

class EntryNotFoundException extends \RuntimeException
{
    /**
     * @param string $id
     *
     * @return EntryNotFoundException
     */
    public static function fromId(string $id)
    {
        return new self(sprintf('Entry with id %s not found within cache', $id));
    }
}
