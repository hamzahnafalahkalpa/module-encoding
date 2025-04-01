<?php

namespace Hanafalah\ModuleEncoding\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\ModuleEncoding\Contracts\Data\ModelHasEncodingData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface ModelHasEncoding extends DataManagement
{
    public function getModelHasEncoding(): mixed;
    public function prepareShowModelHasEncoding(?Model $model = null, ?array $attributes = null): Model;
    public function showModelHasEncoding(?Model $model = null): array;
    public function prepareStoreModelHasEncoding(ModelHasEncodingData $model_has_encoding_dto): Model;
    public function storeModelHasEncoding(? ModelHasEncodingData $model_has_encoding_dto = null): array;
    public function hasEncoding(mixed $conditionals = null): Builder;
    
}
