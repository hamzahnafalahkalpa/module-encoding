<?php

namespace Hanafalah\ModuleEncoding\Contracts\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\LaravelSupport\Contracts\Data\PaginateData;
use Hanafalah\ModuleEncoding\Contracts\Data\EmployeeData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface Employee extends DataManagement
{
    public function getEmployee(): mixed;
    public function prepareShowEmployee(?Model $model = null, ?array $attributes = null): Model;
    public function showEmployee(?Model $model = null): array;
    public function prepareShowProfile(?Model $model = null, ?array $attributes = null): Model;
    public function showProfile(?Model $model = null): array;
    public function prepareStoreEmployee(EmployeeData $employee_dto): Model;
    public function storeEmployee(? EmployeeData $employee_dto = null): array;
    public function prepareViewEmployeePaginate(PaginateData $paginate_dto): LengthAwarePaginator;
    public function viewEmployeePaginate(? PaginateData $paginate_dto = null): array;
    public function prepareViewEmployeeList(): Collection;
    public function viewEmployeeList(): array;
    public function prepareDeleteEmployee(? array $attributes = null): bool;
    public function deleteEmployee(): bool;
    public function employee(mixed $conditionals = null): Builder;
    
    
}
