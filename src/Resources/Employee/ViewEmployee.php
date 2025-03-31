<?php

namespace Hanafalah\ModuleEncoding\Resources\Employee;

use Hanafalah\LaravelSupport\Resources\ApiResource;
use Hanafalah\ModulePeople\Resources\People\ViewPeople;

class ViewEmployee extends ApiResource
{
    public function toArray(\Illuminate\Http\Request $request): array
    {
        $arr = [
            'id'               => $this->id,
            'uuid'             => $this->uuid,
            'card_identity'    => $this->prop_card_identity,
            'people'           => $this->propResource($this->people, ViewPeople::class, ['id']),
            'status'           => $this->status,
            'profile'          => $this->profile ?? null,
            'employee_service' => $this->relationValidation('employeeService', function () {
                return $this->employeeService->toViewApi();
            }),            
            'profession'     => $this->relationValidation('profession', function () {
                $profession = $this->profession;
                return $profession->toShowApi();
            })
        ];

        return $arr;
    }
}
