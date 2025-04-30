<?php

namespace Hanafalah\ModuleEncoding\Schemas;

use Illuminate\Database\Eloquent\{
    Model
};
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleEncoding\Contracts\Data\EncodingData;
use Hanafalah\ModuleEncoding\Contracts\Schemas\Encoding as ContractsEncoding;

class Encoding extends PackageManagement implements ContractsEncoding
{
    protected string $__entity = 'Encoding';
    public static $encoding_model;

    protected array $__cache = [
        'index' => [
            'name'     => 'encoding',
            'tags'     => ['encoding', 'encoding-index'],
            'duration' => 60 * 24 * 7
        ]
    ];

    public function prepareStoreEncoding(EncodingData $encoding_dto): Model{
        $encoding = $this->encoding()->updateOrCreate([
            'flag' => $encoding_dto->flag
        ],[
            'name' => $encoding_dto->name
        ]);
        if (isset($encoding_dto->model_has_encoding)){
            $model_has_encoding = &$encoding_dto->model_has_encoding;
            $model_has_encoding->encoding_id = $encoding->getKey();
            $this->schemaContract('model_has_encoding')->prepareStoreModelHasEncoding($model_has_encoding);
        }
        return static::$encoding_model = $encoding;
    }

    // public function storeEncoding(? EncodingData $encoding_dto = null): array{
    //     return $this->transaction(function () use ($encoding_dto) {
    //         return $this->showEncoding($this->prepareStoreEncoding($encoding_dto ?? $this->requestDTO(EncodingData::class)));
    //     });
    // }

    // public function prepareViewEncodingList(): Collection{
    //     return $this->encoding()->with($this->viewUsingRelation())->get();
    // }

    // public function viewEncodingList(): array{
    //     return $this->viewEntityResource(function(){
    //         return $this->prepareViewEncodingList();
    //     });
    // }

    public function prepareDeleteEncoding(? array $attributes = null): bool{
        $attributes ??= request()->all();
        if (!isset($attributes['id']) && !isset($attributes['uuid'])){
            throw new \Exception('id or uuid not found');
        }

        $encoding = $this->encoding()
            ->when(isset($attributes['id']),function($query) use ($attributes){
                $query->where('id', $attributes['id']);
            })
            ->when(isset($attributes['uuid']),function($query) use ($attributes){
                $query->whereHas('userReference',function($query) use ($attributes){
                    $query->where('uuid', $attributes['uuid']);
                });
            })
            ->firstOrFail();
        return $encoding->delete();
    }

    // public function deleteEncoding(): bool{
    //     return $this->transaction(function(){
    //         return $this->prepareDeleteEncoding();
    //     });
    // }

    // public function encoding(mixed $conditionals = null): Builder{
    //     $this->booting();
    //     return $this->EncodingModel()->with($this->viewUsingRelation())
    //                 ->conditionals($this->mergeCondition($conditionals))
    //                 ->withParameters();
    // }
}

