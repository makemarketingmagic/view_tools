<?php

namespace Makemarketingmagic\ViewTools;

use Illuminate\Support\ServiceProvider;

class ViewToolsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'view_tools');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/view_tools'),
        ], ['views']);
        // Load as 'view_tools::tables.table'
        $this->publishes([
            __DIR__ . '/../config/view_tools_tables.php' => config_path('view_tools_tables.php'),
        ], ['config']);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
