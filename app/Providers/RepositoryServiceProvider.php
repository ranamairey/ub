<?php

namespace App\Providers;

use App\Repositories\AccessRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\AccessRepositoryInterface;

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
