<?php

namespace unasp;

use Illuminate\Support\ServiceProvider;

class UnaspIntegrationsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/unasp_integrations.php' => config_path('unasp_integrations.php'),
        ]);
    }
}
