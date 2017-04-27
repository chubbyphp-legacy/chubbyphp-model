<?php

declare(strict_types=1);

namespace MyProject\Model;

use Chubbyphp\Model\ModelInterface;
use Ramsey\Uuid\Uuid;

final class MyEmbeddedModel implements ModelInterface, \JsonSerializable
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
     * @return MyEmbeddedModel
     */
    public static function create(string $id = null): MyEmbeddedModel
    {
        $myEmbeddedModel = new self;
        $myEmbeddedModel->id = $id ?? (string) Uuid::uuid4();

        return $myEmbeddedModel;
    }

    private function __construct()
    {
    }

    /**
     * @param array $data
     *
     * @return MyEmbeddedModel|ModelInterface
     */
    public static function fromPersistence(array $data): ModelInterface
    {
        $model = new self;
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
     * @return MyEmbeddedModel
     */
    public function setName(string $name): MyEmbeddedModel
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
            'name' => $this->name
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
