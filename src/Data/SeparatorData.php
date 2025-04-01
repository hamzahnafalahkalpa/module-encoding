<?php

namespace Hanafalah\ModuleEncoding\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleEncoding\Contracts\Data\SeparatorData as DataSeparatorData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class SeparatorData extends Data implements DataSeparatorData{
    public function __construct(
        #[MapInputName('distance')]
        #[MapName('distance')]
        public int $distance
    ){}
}