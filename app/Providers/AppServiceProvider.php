<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use App\Observers\SocietyObserver;
use App\Models\Society;
use App\Observers\BlockObserver;
use App\Models\Block;
use App\Observers\PlotObserver;
use App\Models\Plot;
use App\Observers\FlatObserver;
use App\Models\Flat;
use App\Observers\MaintenanceObserver;
use App\Models\Maintenance;

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
        //
        Schema::defaultStringLength(191);
        JsonResource::withoutWrapping();
        
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class,
        );

        Society::observe(SocietyObserver::class);
        Block::observe(BlockObserver::class);
        Plot::observe(PlotObserver::class);
        Flat::observe(FlatObserver::class);
        Maintenance::observe(MaintenanceObserver::class);
    }
}
