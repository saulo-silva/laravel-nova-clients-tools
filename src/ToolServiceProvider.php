<?php

namespace Xtrategie\Clients;

use Xtrategie\Clients\Observers\ClientObserver;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Xtrategie\Clients\Http\Middleware\Authorize;
use Xtrategie\Clients\Models\Client;
use Xtrategie\Clients\Policies\ClientPolicy;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Client::class => ClientPolicy::class,
    ];
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'clients');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            //
        });

        Client::observe(ClientObserver::class);
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        $this->registerPolicies();

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/clients')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
