<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Reference;

use Chubbyphp\Model\ModelInterface;

interface ModelReferenceInterface extends \JsonSerializable
{
    /**
     * @param ModelInterface|null $model
     * @return ModelReferenceInterface
     */
    public function setModel(ModelInterface $model = null): ModelReferenceInterface;

    /**
     * @return ModelInterface|null
     */
    public function getModel();

    /**
     * @return string|null
     */
    public function getId();

    /**
     * @return ModelInterface|null
     */
    public function getInitialModel();
}
