<?php

namespace App\Providers;

use App\Repository\EloquentPropertyRepository;
use App\Repository\EloquentUserRepository;
use App\Repository\IPropertyRepository;
use App\Repository\IUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IUserRepository::class, EloquentUserRepository::class);
        $this->app->bind(IPropertyRepository::class, EloquentPropertyRepository::class);

    }
}
