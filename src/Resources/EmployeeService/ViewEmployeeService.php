<?php

namespace Hanafalah\ModuleEncoding\Resources\EmployeeService;

use Hanafalah\ModuleService\Resources\ViewService;

class ViewEmployeeService extends ViewService
{
    public function toArray(\Illuminate\Http\Request $request): array
    {
        $arr = [];
        $arr = array_merge(parent::toArray($request), $arr);

        return $arr;
    }
}
