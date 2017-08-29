<?php

declare(strict_types=1);

namespace MyProject\Model;

use Chubbyphp\Model\ModelInterface;

final class MyEmbeddedModelNoJsonSerialize implements ModelInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $modelId;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string|null $id
     *
     * @return self
     */
    public static function create(string $id = null): self
    {
        $model = new self();
        $model->id = $id ?? uniqid('id');

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @param array $data
     *
     * @return self|ModelInterface
     */
    public static function fromPersistence(array $data): ModelInterface
    {
        $model = new self();
        $model->id = $data['id'];
        $model->modelId = $data['modelId'];
        $model->name = $data['name'];

        return $model;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            'modelId' => $this->modelId,
            'name' => $this->name,
        ];
    }
}
