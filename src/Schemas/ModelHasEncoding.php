<?php

namespace Hanafalah\ModuleEncoding\Schemas;

use Illuminate\Database\Eloquent\{
    Builder, Collection, Model
};
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleEncoding\Contracts\Data\ModelHasEncodingData;
use Hanafalah\ModuleEncoding\Contracts\Schemas\ModelHasEncoding as ContractsModelHasEncoding;

class ModelHasEncoding extends PackageManagement implements ContractsModelHasEncoding
{
    protected string $__entity = 'ModelHasEncoding';
    public static $model_has_encoding_model;

    public function getModelHasEncoding(): mixed{
        return static::$model_has_encoding_model;
    }

    public function prepareShowModelHasEncoding(?Model $model = null, ?array $attributes = null): Model{
        $attributes ??= request()->all();

        $model ??= $this->getModelHasEncoding();
        if (!isset($model)) {
            $id   = $attributes['id'] ?? null;
            if (!isset($id)) throw new \Exception('id is not found');

            $model = $this->hasEncoding()->with($this->showUsingRelation())->findOrFail($id);            
        } else {
            $model->load($this->showUsingRelation());
        }
        return static::$model_has_encoding_model = $model;
    }    

    public function showModelHasEncoding(?Model $model = null): array{
        return $this->showEntityResource(function() use ($model){
            return $this->prepareShowModelHasEncoding($model);
        });
    }

    public function prepareStoreModelHasEncoding(ModelHasEncodingData $model_has_encoding_dto): Model{
        $model = $this->ModelHasEncodingModel()->updateOrCreate([
            'encoding_id'    => $model_has_encoding_dto->encoding_id,
            'reference_id'   => $model_has_encoding_dto->reference_id,
            'reference_type' => $model_has_encoding_dto->reference_type
        ]);
        foreach ($model_has_encoding_dto->props as $key => $prop) {
            $model->{$key} = $prop;
        }
        $model->save();
        return static::$model_has_encoding_model = $model;
    }

    public function storeModelHasEncoding(? ModelHasEncodingData $model_has_encoding_dto = null): array{
        return $this->transaction(function () use ($model_has_encoding_dto) {
            return $this->showModelHasEncoding($this->prepareStoreModelHasEncoding($model_has_encoding_dto ?? $this->requestDTO(ModelHasEncodingData::class)));
        });
    }

    public function hasEncoding(mixed $conditionals = null): Builder{
        $this->booting();
        return $this->ModelHasEncodingModel()
                    ->conditionals($this->mergeCondition($conditionals))
                    ->withParameters();
    }
}

