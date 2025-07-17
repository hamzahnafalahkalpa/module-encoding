<?php

namespace Hanafalah\ModuleEncoding\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleEncoding\Contracts\Data\EncodingData as DataEncodingData;
use Hanafalah\ModuleEncoding\Contracts\Data\ModelHasEncodingData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class EncodingData extends Data implements DataEncodingData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('name')]
    #[MapName('name')]
    public ?string $name = null;

    #[MapInputName('flag')]
    #[MapName('flag')]
    public string $flag;

    #[MapInputName('model_has_encoding')]
    #[MapName('model_has_encoding')]
    public ?ModelHasEncodingData $model_has_encoding;
}