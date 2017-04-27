<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Reference;

use Chubbyphp\Model\ModelInterface;

final class ModelReference implements ModelReferenceInterface
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var ModelInterface|null
     */
    private $model;

    /**
     * @param ModelInterface|null $model
     * @return ModelReferenceInterface
     */
    public function setModel(ModelInterface $model = null): ModelReferenceInterface
    {
        $this->model = $model;
        $this->id = null !== $model ? $model->getId() : null;

        return $this;
    }

    /**
     * @return ModelInterface|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ModelInterface|null
     */
    public function getInitialModel()
    {
        return null;
    }

    /**
     * @return array|null
     */
    public function jsonSerialize()
    {
        if (null === $this->model) {
            return null;
        }

        return $this->model->jsonSerialize();
    }
}
