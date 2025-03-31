<?php

namespace Hanafalah\ModuleEncoding\Models\Employee;

use Hanafalah\ModuleService\Models\Service;
use Hanafalah\ModuleEncoding\Resources\EmployeeService\{
    ViewEmployeeService,
    ShowEmployeeService
};

class EmployeeService extends Service
{
    protected $table = 'services';

    public function toViewApi()
    {
        return new ViewEmployeeService($this);
    }

    public function toShowApi()
    {
        return new ShowEmployeeService($this);
    }
}
