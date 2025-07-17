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

    public function prepareStoreModelHasEncoding(ModelHasEncodingData $model_has_encoding_dto): Model{
        $model = $this->usingEntity()->updateOrCreate([
            'encoding_id'    => $model_has_encoding_dto->encoding_id,
            'reference_id'   => $model_has_encoding_dto->reference_id,
            'reference_type' => $model_has_encoding_dto->reference_type
        ],[
            'value' => $model_has_encoding_dto->value
        ]);
        $this->fillingProps($model,$model_has_encoding_dto->props);
        $model->save();
        return static::$model_has_encoding_model = $model;
    }
}

