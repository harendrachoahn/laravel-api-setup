<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\SellerRepositoryInterface;
use App\Repositories\Interfaces\BuyerRepositoryInterface;

use App\Repositories\SellerRepository;
use App\Repositories\BuyerRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSellerRepo();
        $this->registerBuyerRepo();
    }


    public function registerSellerRepo()
    {
        return $this->app->bind(
            SellerRepositoryInterface::class,
            SellerRepository::class
        );        
    }

    public function registerBuyerRepo()
    {
        return $this->app->bind(
            BuyerRepositoryInterface::class,
            BuyerRepository::class
        );
        
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

