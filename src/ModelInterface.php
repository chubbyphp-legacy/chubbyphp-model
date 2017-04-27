<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

interface ModelInterface
{
    /**
     * @param array $data
     *
     * @return ModelInterface
     */
    public static function fromPersistence(array $data): ModelInterface;

    /**
     * @return array
     */
    public function toPersistence(): array;

    /**
     * @return string
     */
    public function getId(): string;
}
