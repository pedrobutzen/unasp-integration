<?php

namespace unasp;

use Illuminate\Support\ServiceProvider;

class UnaspIntegrationsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/unasp_integrations.php' => config_path('unasp_integrations.php'),
            __DIR__ . '/config/2019_10_08_111214_create_table_integrator_log.php' => database_path('migrations/2019_10_08_111214_create_table_integrator_log.php'),
        ]);
    }
}
