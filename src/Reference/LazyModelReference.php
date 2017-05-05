<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Reference;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\ResolverInterface;

final class LazyModelReference implements ModelReferenceInterface
{
    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var bool
     */
    private $resolved = false;

    /**
     * @var ModelInterface|null
     */
    private $initialModel;

    /**
     * @var ModelInterface|null
     */
    private $model;

    /**
     * @param ResolverInterface $resolver
     * @param string            $modelClass
     * @param string|null       $id
     */
    public function __construct(ResolverInterface $resolver, string $modelClass, string $id = null)
    {
        $this->resolver = $resolver;
        $this->modelClass = $modelClass;
        $this->id = $id;
    }

    private function resolveModel()
    {
        if ($this->resolved) {
            return;
        }

        $this->resolved = true;

        $this->initialModel = $this->resolver->find($this->modelClass, $this->id);
        $this->model = $this->initialModel;
    }

    /**
     * @param ModelInterface|null $model
     *
     * @return ModelReferenceInterface
     */
    public function setModel(ModelInterface $model = null): ModelReferenceInterface
    {
        $this->resolveModel();

        $this->model = $model;
        $this->id = null !== $model ? $model->getId() : null;

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
        return $this->id;
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

        $this->jsonSerializableOrException();

        return $this->model->jsonSerialize();
    }

    /**
     * @throws \LogicException
     */
    private function jsonSerializableOrException()
    {
        if (!$this->model instanceof \JsonSerializable) {
            throw new \LogicException(
                sprintf('Model %s does not implement %s', get_class($this->model), \JsonSerializable::class)
            );
        }
    }
}
