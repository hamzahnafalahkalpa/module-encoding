<?php

namespace Hanafalah\ModuleEncoding\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\ModuleEncoding\Contracts\Data\ModelHasEncodingData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleEncoding\Schemas\ModelHasEncoding
 * @method self setParamLogic(string $logic, bool $search_value = false, ?array $optionals = [])
 * @method self conditionals(mixed $conditionals)
 * @method bool deleteModelHasEncoding()
 * @method mixed getModelHasEncoding()
 * @method ?Model prepareShowModelHasEncoding(?Model $model = null, ?array $attributes = null)
 * @method array showModelHasEncoding(?Model $model = null)
 * @method Collection prepareViewModelHasEncodingList()
 * @method array viewModelHasEncodingList()
 * @method LengthAwarePaginator prepareViewModelHasEncodingPaginate(PaginateData $paginate_dto)
 * @method array viewModelHasEncodingPaginate(?PaginateData $paginate_dto = null)
 * @method array storeModelHasEncoding(?ModelHasEncodingData $model_has_encoding_dto = null)
 * @method Builder modelHasEncoding(mixed $conditionals = null)
 */
interface ModelHasEncoding extends DataManagement
{
    public function prepareStoreModelHasEncoding(ModelHasEncodingData $model_has_encoding_dto): Model;
}
