<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Accounting\Services\AccountingService;
use App\Domain\Accounting\Services\BillingService;

/**
 * DomainServiceProvider
 *
 * Registers all domain-specific services, middleware aliases, and model observations.
 */
class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind AccountingService as singleton (stateless)
        $this->app->singleton(AccountingService::class, function () {
            return new AccountingService();
        });

        // Bind BillingService with dependency injection
        $this->app->singleton(BillingService::class, function ($app) {
            return new BillingService(
                $app->make(AccountingService::class)
            );
        });
    }

    public function boot(): void
    {
        // Register middleware aliases
        $router = $this->app['router'];
        $router->aliasMiddleware('set.society', \App\Domain\Shared\Middleware\SetCurrentSociety::class);
        $router->aliasMiddleware('superadmin', \App\Http\Middleware\EnsureIsSuperAdmin::class);
        $router->aliasMiddleware('accounting.integrity', \App\Domain\Shared\Middleware\EnsureAccountingIntegrity::class);
    }
}
