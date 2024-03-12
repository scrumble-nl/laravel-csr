<?php

declare(strict_types=1);

namespace Scrumble\Csr;

use Scrumble\Csr\Commands\CreateCsr;
use Scrumble\Csr\Commands\SubCommand\CreateModel;
use Scrumble\Csr\Commands\SubCommand\CreatePolicy;
use Scrumble\Csr\Commands\SubCommand\CreateService;
use Scrumble\Csr\Commands\SubCommand\CreateIService;
use Scrumble\Csr\Commands\SubCommand\CreateRepository;
use Scrumble\Csr\Commands\SubCommand\CreateController;
use Scrumble\Csr\Commands\SubCommand\CreateIRepository;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class CsrServiceProvider extends LaravelServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->handleConfigs();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            CreateCsr::class,
            CreateModel::class,
            CreatePolicy::class,
            CreateController::class,
            CreateIService::class,
            CreateService::class,
            CreateIRepository::class,
            CreateRepository::class,
        ]);
    }

    /**
     * Fetches and publishes config file
     *
     * @return void
     */
    private function handleConfigs(): void
    {
        $configPath = __DIR__ . '/../config/csr.php';
        $this->publishes([$configPath => config_path('csr.php')], 'laravel-csr');
    }
}
