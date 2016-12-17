<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Reference;

use Chubbyphp\Model\ModelInterface;

final class ModelReference implements ModelReferenceInterface
{
    /**
     * @var ModelInterface|null
     */
    private $initialModel;

    /**
     * @var ModelInterface|null
     */
    private $model;

    /**
     * @param ModelInterface|null $model
     */
    public function __construct(ModelInterface $model = null)
    {
        $this->initialModel = $model;
        $this->model = $model;
    }

    public function setModel(ModelInterface $model = null): ModelReferenceInterface
    {
        $this->model = $model;

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
        if (null === $this->model) {
            return null;
        }

        return $this->model->getId();
    }

    /**
     * @return ModelInterface|null
     */
    public function getInitialModel()
    {
        return $this->initialModel;
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
