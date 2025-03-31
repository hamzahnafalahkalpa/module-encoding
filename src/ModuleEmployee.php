<?php

namespace Hanafalah\ModuleEncoding;

use Hanafalah\LaravelSupport\Supports\PackageManagement;

class ModuleEncoding extends PackageManagement implements Contracts\ModuleEncoding
{
    /** @var array */
    protected $__module_employee_config = [];

    /**
     * A description of the entire PHP function.
     *
     * @param Container $app The Container instance
     * @throws Exception description of exception
     * @return void
     */
    public function __construct()
    {
        $this->setConfig('module-encoding', $this->__module_employee_config);
    }
}
