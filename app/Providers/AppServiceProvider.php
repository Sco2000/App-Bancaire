<?php

namespace App\Providers;

use App\Http\Interfaces\RepositoryInterfaces\IFindByIdRepository;
use App\Models\User;
use App\Http\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Http\Interfaces\RepositoryInterfaces\IUserRepository;
use App\Http\Services\AuthService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(IUserRepository::class, function($app) {
            return new UserRepository(new User());
        });

        $this->app->singleton(IFindByIdRepository::class, function($app) {
            return new UserRepository(new User());
        });

        $this->app->singleton(AuthService::class, function($app) {
            return new AuthService($app->make(IUserRepository::class), $app->make(IFindByIdRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
