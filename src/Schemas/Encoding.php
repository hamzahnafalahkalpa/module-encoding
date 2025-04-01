<?php

namespace Hanafalah\ModuleEncoding\Schemas;

use Illuminate\Database\Eloquent\{
    Builder, Collection, Model
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

    protected function viewUsingRelation(): array{
        return [];
    }

    protected function showUsingRelation(): array{
        return [
            'modelHasEncoding'
        ];
    }

    public function getEncoding(): mixed{
        return static::$encoding_model;
    }

    public function prepareShowEncoding(?Model $model = null, ?array $attributes = null): Model{
        $attributes ??= request()->all();

        $model ??= $this->getEncoding();
        if (!isset($model)) {
            $id   = $attributes['id'] ?? null;
            if (!isset($id)) throw new \Exception('id is not found');

            $model = $this->encoding()->with($this->showUsingRelation())->findOrFail($id);            
        } else {
            $model->load($this->showUsingRelation());
        }
        return static::$encoding_model = $model;
    }    

    public function showEncoding(?Model $model = null): array{
        return $this->showEntityResource(function() use ($model){
            return $this->prepareShowEncoding($model);
        });
    }

    public function prepareStoreEncoding(EncodingData $encoding_dto): Model{
        $encoding = $this->encoding()->updateOrCreate([
            'flag' => $encoding_dto->flag
        ],[
            'name' => $encoding_dto->name
        ]);

        if (isset($encoding_dto->modelHasEncoding)){
            
        }
        return static::$encoding_model = $encoding;
    }

    public function storeEncoding(? EncodingData $encoding_dto = null): array{
        return $this->transaction(function () use ($encoding_dto) {
            return $this->showEncoding($this->prepareStoreEncoding($encoding_dto ?? $this->requestDTO(EncodingData::class)));
        });
    }

    public function prepareViewEncodingList(): Collection{
        return $this->encoding()->with($this->viewUsingRelation())->get();
    }

    public function viewEncodingList(): array{
        return $this->viewEntityResource(function(){
            return $this->prepareViewEncodingList();
        });
    }

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

    public function deleteEncoding(): bool{
        return $this->transaction(function(){
            return $this->prepareDeleteEncoding();
        });
    }

    public function encoding(mixed $conditionals = null): Builder{
        $this->booting();
        return $this->EncodingModel()->conditionals($this->mergeCondition($conditionals))->withParameters('or')->orderBy('props->prop_people->name','asc');
    }
}

