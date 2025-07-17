<?php

namespace Hanafalah\ModuleEncoding\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleEncoding\Contracts\Data\StructureData as DataStructureData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Contracts\BaseData;

class StructureData extends Data implements DataStructureData, BaseData{
    #[MapInputName('type')]
    #[MapName('type')]
    public string $type;

    #[MapInputName('value')]
    #[MapName('value')]
    public ?string $value = null;

    #[MapInputName('length')]
    #[MapName('length')]
    public ?int $length;

    #[MapInputName('resetable')]
    #[MapName('resetable')]
    public ?bool $resetable = null;

    #[MapInputName('format')]
    #[MapName('format')]
    public ?string $format = null;

    public static function after(StructureData $data): StructureData{
        if (!isset($data->length)){
            if (isset($data->value)){
                $data->length = strlen($data->value);
            }
        }
        return $data;
    }
}