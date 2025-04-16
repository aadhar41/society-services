<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\SocietyRepositoryInterface;
use App\Repositories\SocietyRepository;
use App\Interfaces\BlockRepositoryInterface;
use App\Interfaces\FlatRepositoryInterface;
use App\Interfaces\PlotRepositoryInterface;
use App\Repositories\BlockRepository;
use App\Repositories\FlatRepository;
use App\Repositories\PlotRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SocietyRepositoryInterface::class, SocietyRepository::class);
        $this->app->bind(BlockRepositoryInterface::class, BlockRepository::class);
        $this->app->bind(PlotRepositoryInterface::class, PlotRepository::class);
        $this->app->bind(FlatRepositoryInterface::class, FlatRepository::class);
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
