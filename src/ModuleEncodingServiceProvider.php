<?php

declare(strict_types=1);

namespace Hanafalah\ModuleEncoding;

use Hanafalah\LaravelSupport\Providers\BaseServiceProvider;

class ModuleEncodingServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     * 
     * @return $this
     */
    public function register()
    {
        $this->registerMainClass(ModuleEncoding::class)
            ->registerCommandService(Providers\CommandServiceProvider::class)
            ->registers(['*']);
    }
    

    /**
     * Get the base path of the package.
     *
     * @return string
     */
    protected function dir(): string
    {
        return __DIR__ . '/';
    }
}
