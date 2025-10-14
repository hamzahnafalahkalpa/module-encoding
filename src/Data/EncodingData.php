<?php

namespace Hanafalah\ModuleEncoding\Data;

use Hanafalah\LaravelSupport\Data\UnicodeData;
use Hanafalah\ModuleEncoding\Contracts\Data\EncodingData as DataEncodingData;
use Hanafalah\ModuleEncoding\Contracts\Data\ModelHasEncodingData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class EncodingData extends UnicodeData implements DataEncodingData{
    #[MapInputName('model_has_encoding')]
    #[MapName('model_has_encoding')]
    public ?ModelHasEncodingData $model_has_encoding;

    public static function before(array &$attributes){
        $attributes['flag'] ??= 'Encoding';
        parent::before($attributes);
    }
}