<?php

namespace Hanafalah\ModuleEncoding\Schemas;

use Hanafalah\LaravelSupport\Schemas\Unicode;
use Illuminate\Database\Eloquent\{
    Builder,
    Model
};
use Hanafalah\ModuleEncoding\Contracts\Data\EncodingData;
use Hanafalah\ModuleEncoding\Contracts\Schemas\Encoding as ContractsEncoding;

class Encoding extends Unicode implements ContractsEncoding
{
    protected string $__entity = 'Encoding';
    public $encoding_model;

    protected array $__cache = [
        'index' => [
            'name'     => 'encoding',
            'tags'     => ['encoding', 'encoding-index'],
            'duration' => 60 * 24 * 7
        ]
    ];

    public function prepareStoreEncoding(EncodingData $encoding_dto): Model{
        $encoding = $this->prepareStoreUnicode($encoding_dto);
        if (isset($encoding_dto->model_has_encoding)){
            $model_has_encoding = &$encoding_dto->model_has_encoding;
            $model_has_encoding->encoding_id = $encoding->getKey();
            $this->schemaContract('model_has_encoding')->prepareStoreModelHasEncoding($model_has_encoding);
        }
        return $this->encoding_model = $encoding;
    }

    public function encoding(mixed $conditionals = null): Builder{
        return $this->unicode($conditionals);
    }
}

