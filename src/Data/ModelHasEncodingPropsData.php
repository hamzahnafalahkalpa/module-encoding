<?php

namespace Hanafalah\ModuleEncoding\Data;

use Hanafalah\LaravelSupport\Concerns\Support\HasRequestData;
use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleEncoding\Contracts\Data\ModelHasEncodingPropsData as DataModelHasEncodingPropsData;
use Hanafalah\ModuleEncoding\Contracts\Data\StructureData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ModelHasEncodingPropsData extends Data implements DataModelHasEncodingPropsData{
    use HasRequestData;
    
    #[MapInputName('separator')]
    #[MapName('separator')]
    public ?SeparatorData $separator = null;

    #[MapInputName('structure')]
    #[MapName('structure')]
    public ?array $structure = null;

    public static function after(ModelHasEncodingPropsData $data): ModelHasEncodingPropsData{
        $new = static::new();
        if (isset($data->structure)){
            $data->structure = array_map(fn ($item) => $new->requestDTO(StructureData::class,$item), $data->structure);
        }
        return $data;
    }
}