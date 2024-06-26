<?php

namespace App\Providers;

use App\Repositories\AccessRepository;
use App\Repositories\AdviceRepository;
use App\Repositories\AccountRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ActivityRepository;
use App\Interfaces\AccessRepositoryInterface;
use App\Interfaces\AdviceRepositoryInterface;
use App\Interfaces\AccountRepositoryInterface;
use App\Interfaces\ActivityRepositoryInterface;

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
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(AdviceRepositoryInterface::class,AdviceRepository::class);
    
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
