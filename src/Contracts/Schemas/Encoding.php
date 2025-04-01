<?php

namespace Hanafalah\ModuleEncoding\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\ModuleEncoding\Contracts\Data\EncodingData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface Encoding extends DataManagement
{
    public function getEncoding(): mixed;
    public function prepareShowEncoding(?Model $model = null, ?array $attributes = null): Model;
    public function showEncoding(?Model $model = null): array;
    public function prepareStoreEncoding(EncodingData $encoding_dto): Model;
    public function storeEncoding(? EncodingData $encoding_dto = null): array;
    public function prepareViewEncodingList(): Collection;
    public function viewEncodingList(): array;
    public function prepareDeleteEncoding(? array $attributes = null): bool;
    public function deleteEncoding(): bool;
    public function encoding(mixed $conditionals = null): Builder;
}
