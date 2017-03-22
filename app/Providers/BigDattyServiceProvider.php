<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BigDattyServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/bigdatty.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('bigdatty.php');
        }
        else {
            $publishPath = base_path('config/bigdatty.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../../config/bigdatty.php';
        $this->mergeConfigFrom($configPath, 'bigdatty');
    }
}