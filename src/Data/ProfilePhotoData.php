<?php

namespace Hanafalah\ModuleEncoding\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleEncoding\Contracts\Data\ProfilePhotoData as DataProfilePhotoData;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ProfilePhotoData extends Data implements DataProfilePhotoData{
    public function __construct(
        #[MapInputName('id')]
        #[MapName('id')]
        public mixed $id = null,

        #[MapInputName('uuid')]
        #[MapName('uuid')]
        public ?string $uuid = null,

        #[MapInputName('profile')]
        #[MapName('profile')]
        public string|UploadedFile|null $profile = null,
    ){}
}