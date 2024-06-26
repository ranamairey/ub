<?php

namespace App\Providers;

use App\Repositories\AccessRepository;
use App\Repositories\AccountRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\AccessRepositoryInterface;
use App\Interfaces\AccountRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AccessRepositoryInterface::class, AccessRepository::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
    
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
