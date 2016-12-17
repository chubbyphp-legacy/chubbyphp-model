<?php

declare(strict_types=1);

namespace MyProject\Model;

use Chubbyphp\Model\Collection\ModelCollection;
use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\Reference\ModelReference;
use Ramsey\Uuid\Uuid;

final class MyModel implements ModelInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $category;

    /**
     * @var ModelReference
     */
    private $oneToOne;

    /**
     * @var ModelCollection
     */
    private $oneToMany;

    /**
     * @param string|null $id
     */
    public function __construct(string $id = null)
    {
        $this->id = $id ?? (string) Uuid::uuid4();
        $this->oneToOne = new ModelReference();
        $this->oneToMany = new ModelCollection();
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
     * @return self
     */
    public function setName(string $name): MyModel
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
     * @param string $category
     * @return self
     */
    public function setCategory(string $category): MyModel
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param MyEmbeddedModel|null $oneToOne
     * @return self
     */
    public function setOneToOne(MyEmbeddedModel $oneToOne = null): MyModel
    {
        $this->oneToOne->setModel($oneToOne);

        return $this;
    }

    /**
     * @return MyEmbeddedModel|ModelInterface|null
     */
    public function getOneToOne()
    {
        return $this->oneToOne->getModel();
    }

    /**
     * @param MyEmbeddedModel[]|array $oneToMany
     * @return $this
     */
    public function setOneToMany(array $oneToMany)
    {
        $this->oneToMany->setModels($oneToMany);

        return $this;
    }

    /**
     * @return MyEmbeddedModel[]|ModelInterface[]|array
     */
    public function getOneToMany()
    {
        return $this->oneToMany->getModels();
    }

    /**
     * @param array $data
     *
     * @return ModelInterface
     */
    public static function fromPersistence(array $data): ModelInterface
    {
        $model = new self($data['id']);
        $model->name = $data['name'];
        $model->category = $data['category'];
        $model->oneToOne = $data['oneToOne'];
        $model->oneToMany = $data['oneToMany'];

        return $model;
    }

    /**
     * @return array
     */
    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'oneToOne' => $this->oneToOne,
            'oneToMany' => $this->oneToMany
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'oneToOne' => $this->oneToOne->jsonSerialize(),
            'oneToMany' => $this->oneToMany->jsonSerialize()
        ];
    }
}
