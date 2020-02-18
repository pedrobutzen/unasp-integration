<?php

namespace unasp;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class UnaspIntegrationsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            'unasp\Console\Commands\ProcessaFilaDeChamadas',
        ]);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/unasp_integrations.php' => config_path('unasp_integrations.php'),
            __DIR__ . '/config/2019_10_08_111214_create_table_integrator_log.php' => database_path('migrations/2019_10_08_111214_create_table_integrator_log.php'),
            __DIR__ . '/config/2020_01_29_182717_create_table_integrator_queue.php' => database_path('migrations/2020_01_29_182717_create_table_integrator_queue.php'),
        ]);

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('integrator:ProcessaFilaDeChamadas')->everyMinute();
        });
    }
}
