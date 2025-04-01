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

    public function __construct(
        #[MapInputName('separator')]
        #[MapName('separator')]
        public ?SeparatorData $separator = null,

        #[MapInputName('structure')]
        #[MapName('structure')]
        public array $structure,
    ){
        $this->structure = array_map(fn ($item) => $this->requestDTO(StructureData::class,$item), $structure);
    }
}