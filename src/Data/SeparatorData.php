<?php

namespace Hanafalah\ModuleEncoding\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleEncoding\Contracts\Data\SeparatorData as DataSeparatorData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class SeparatorData extends Data implements DataSeparatorData{
    #[MapInputName('distance')]
    #[MapName('distance')]
    public int $distance;

    #[MapInputName('separator')]
    #[MapName('separator')]
    public ?string $separator = '';
}