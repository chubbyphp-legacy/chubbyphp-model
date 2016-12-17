<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Reference;

use Chubbyphp\Model\ModelInterface;

final class LazyModelReference implements ModelReferenceInterface
{
    /**
     * @var \Closure
     */
    private $resolver;

    /**
     * @var ModelInterface|null
     */
    private $initialModel;

    /**
     * @var ModelInterface|null
     */
    private $model;

    /**
     * @param \Closure $resolver
     */
    public function __construct(\Closure $resolver)
    {
        $this->resolver = $resolver;
    }

    private function resolveModel()
    {
        if (null === $this->resolver) {
            return;
        }

        $resolver = $this->resolver;

        $this->resolver = null;

        $this->model = $resolver();
        $this->initialModel = $this->model;
    }

    /**
     * @param ModelInterface|null $model
     * @return ModelReferenceInterface
     */
    public function setModel(ModelInterface $model = null): ModelReferenceInterface
    {
        $this->resolveModel();

        $this->model = $model;

        return $this;
    }

    /**
     * @return ModelInterface|null
     */
    public function getModel()
    {
        $this->resolveModel();

        return $this->model;
    }

    /**
     * @return null|string
     */
    public function getId()
    {
        $this->resolveModel();

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
        $this->resolveModel();

        return $this->initialModel;
    }

    /**
     * @return array|null
     */
    public function jsonSerialize()
    {
        $this->resolveModel();

        if (null === $this->model) {
            return null;
        }

        return $this->model->jsonSerialize();
    }
}
