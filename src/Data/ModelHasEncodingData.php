<?php

namespace Hanafalah\ModuleEncoding\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleEncoding\Contracts\Data\ModelHasEncodingData as DataModelHasEncodingData;
use Hanafalah\ModuleEncoding\Contracts\Data\ModelHasEncodingPropsData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ModelHasEncodingData extends Data implements DataModelHasEncodingData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('encoding_id')]
    #[MapName('encoding_id')]
    public mixed $encoding_id;

    #[MapInputName('reference_id')]
    #[MapName('reference_id')]
    public string $reference_id;

    #[MapInputName('reference_type')]
    #[MapName('reference_type')]
    public string $reference_type;

    #[MapInputName('value')]
    #[MapName('value')]
    public ?string $value = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?ModelHasEncodingPropsData $props = null;
}