<?php

declare(strict_types=1);

namespace Chubbyphp\Model;

final class RelatedModelManipulationStack
{
    /**
     * @var ModelInterface[]|array
     */
    private $toPersistModels = [];

    /**
     * @var ModelInterface[]|array
     */
    private $toRemoveModels = [];

    /**
     * @param ModelInterface[]|array $models
     * @return RelatedModelManipulationStack
     */
    public function addToPersistModels(array $models): RelatedModelManipulationStack
    {
        foreach ($models as $model) {
            $this->addToPersistModel($model);
        }

        return $this;
    }

    /**
     * @param ModelInterface $model
     * @return RelatedModelManipulationStack
     */
    public function addToPersistModel(ModelInterface $model): RelatedModelManipulationStack
    {
        $this->toPersistModels[$model->getId()] = $model;

        return $this;
    }

    /**
     * @param ModelInterface[]|array $models
     * @return RelatedModelManipulationStack
     */
    public function addToRemoveModels(array $models): RelatedModelManipulationStack
    {
        foreach ($models as $model) {
            $this->addToRemoveModel($model);
        }

        return $this;
    }

    /**
     * @param ModelInterface $model
     * @return RelatedModelManipulationStack
     */
    public function addToRemoveModel(ModelInterface $model): RelatedModelManipulationStack
    {
        $this->toRemoveModels[$model->getId()] = $model;

        return $this;
    }

    /**
     * @return ModelInterface[]|array
     */
    public function getToPersistModels(): array
    {
        return $this->toPersistModels;
    }

    /**
     * @return ModelInterface[]|array
     */
    public function getToRemoveModels(): array
    {
        $toRemoveModels = $this->toRemoveModels;
        foreach ($toRemoveModels as $toRemoveModel) {
            if (isset($this->toPersistModels[$toRemoveModel->getId()])) {
                unset($toRemoveModels[$toRemoveModel->getId()]);
            }
        }

        return $toRemoveModels;
    }
}
