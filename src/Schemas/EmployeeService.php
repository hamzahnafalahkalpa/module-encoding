<?php

namespace Hanafalah\ModuleEncoding\Schemas;

use Hanafalah\ModuleService\Schemas\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleEncoding\Contracts\Schemas\EmployeeService as ContractsEmployeeService;
use Hanafalah\ModuleEncoding\Resources\EmployeeService\{ViewEmployeeService, ShowEmployeeService};

class EmployeeService extends Service implements ContractsEmployeeService
{
    protected string $__entity = 'EmployeeService';
    public static $employee_service_model;

    protected array $__resources = [
        'view' => ViewEmployeeService::class,
        'show' => ShowEmployeeService::class
    ];

    public function createEmployeeService($employee, array $attributes)
    {
        $employee_service = $employee->employeeService()->updateOrCreate([
            'reference_id'   => $employee->getKey(),
            'reference_type' => $employee->getMorphClass()
        ], [
            'name' => $attributes['name'] ?? 'Employee Service Rates',
        ]);

        return $employee_service;
    }

    public function createPriceComponent($employee, $employee_service, $attributes)
    {
        $price_component_schema = $this->schemaContract('price_component');
        return $price_component_schema->prepareStorePriceComponent([
            'model_id'          => $employee->getKey(),
            'model_type'        => $employee->getMorphClass(),
            'service_id'        => $employee_service->getKey(),
            'service'           => $employee_service,
            'tariff_components' => $attributes['tariff_components']
        ]);
    }

    public function prepareStoreEmployeeService(?array $attributes = null): Model
    {
        $attributes ??= request()->all();
        if (!isset($attributes['employee_id'])) throw new \Exception('employee_id is required');

        $employee = $this->EmployeeModel()->findOrFail($attributes['employee_id']);
        $employee_service = $this->createEmployeeService($employee, $attributes);

        if (isset($attributes['tariff_components']) && count($attributes['tariff_components']) > 0) {
            $this->createPriceComponent($employee, $employee_service, $attributes);
        } else {
            $employee_service->priceComponents()->delete();
        }

        return static::$employee_service_model = $employee;
    }

    public function prepareShowEmployeeService(?Model $model = null, ?array $attributes = null): Model
    {
        $attributes ??= request()->all();

        $model ??= $this->getEmployeeService();
        if (isset($model)) {
            $id = $attributes['id'] ?? null;
            if (!isset($id)) throw new \Exception('id not found');

            $model = $this->employeeService()->with($this->showUsingRelation())->findOrFail($id);
        } else {
            $model->load($this->showUsingRelation());
        }
        return static::$employee_service_model = $model;
    }

    public function showUsingRelation(): array
    {
        return [];
    }

    public function showEmployeeService(?Model $model = null): array
    {
        return $this->transforming($this->__resources['show'], function () use ($model) {
            return $this->prepareShowEmployeeService($model);
        });
    }

    public function storeEmployeeService(): array
    {
        return $this->transaction(function () {
            return $this->showEmployeeService($this->prepareStoreEmployeeService());
        });
    }

    public function getEmployeeService(): mixed
    {
        return static::$employee_service_model;
    }

    public function employeeService(mixed $conditionals = null): Builder
    {
        $this->booting();
        return $this->EmployeeServiceModel()->conditionals($conditionals)
            ->with('priceComponents')->withParameters();
    }
}
