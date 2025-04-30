<?php

namespace Hanafalah\ModuleEncoding\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\ModuleEncoding\Contracts\Data\EncodingData;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleEncoding\Schemas\Encoding
 * @method self conditionals(mixed $conditionals)
 * @method bool deleteEncoding()
 * @method mixed getEncoding()
 * @method ?Model prepareShowEncoding(?Model $model = null, ?array $attributes = null)
 * @method array showEncoding(?Model $model = null)
 * @method Collection prepareViewEncodingList()
 * @method array viewEncodingList()
 * @method LengthAwarePaginator prepareViewEncodingPaginate(PaginateData $paginate_dto)
 * @method array viewEncodingPaginate(?PaginateData $paginate_dto = null)
 * @method array storeEncoding(?EncodingData $Encoding_dto = null)
 * @method Builder encoding(mixed $conditionals = null)
 */
interface Encoding extends DataManagement
{
    public function prepareStoreEncoding(EncodingData $encoding_dto): Model;
    public function prepareDeleteEncoding(? array $attributes = null): bool;
}
