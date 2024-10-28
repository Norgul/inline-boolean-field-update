<?php

namespace Wehaa\LiveupdateBoolean;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Wehaa\LiveupdateBoolean\Http\Controllers\InlineBooleanFieldController;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('liveupdate-boolean', __DIR__ . '/../dist/js/field.js');
            Nova::style('liveupdate-boolean', __DIR__ . '/../dist/css/field.css');
        });

        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) return;

        Route::middleware(['nova'])->prefix('nova-vendor/inline-boolean-field-update')->group(function () {
            Route::post('/update/{resource}', [InlineBooleanFieldController::class, 'update']);
        });
    }
}
