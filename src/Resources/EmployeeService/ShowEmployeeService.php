<?php

namespace Hanafalah\ModuleEncoding\Resources\EmployeeService;

use Hanafalah\ModuleService\Resources\ShowService;

class ShowEmployeeService extends ShowService
{

    public function toArray(\Illuminate\Http\Request $request): array
    {
        $arr = [
            'price_components' => $this->relationValidation('priceComponents', function () {
                return $this->priceComponents->transform(function ($priceComponent) {
                    return $priceComponent->toShowApi();
                });
            })
        ];
        $view = $this->resolveNow(new ViewEmployeeService($this));
        $arr = array_merge(parent::toArray($request), $view, $arr);

        return $arr;
    }
}
