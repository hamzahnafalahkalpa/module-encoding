<?php

namespace Hanafalah\ModuleEncoding\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleEncoding\Contracts\Data\ModelHasEncodingPropsData as DataModelHasEncodingPropsData;
use Hanafalah\ModuleEncoding\Contracts\Data\StructureData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ModelHasEncodingPropsData extends Data implements DataModelHasEncodingPropsData{
    public function __construct(
        #[MapInputName('separator')]
        #[MapName('separator')]
        public ?SeparatorData $separator = null,

        #[MapInputName('structure')]
        #[MapName('structure')]
        public StructureData $structure,
    ){}
}