<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Client;
use App\Models\Invoice;
use App\Policies\ClientPolicy;
use App\Policies\InvoicePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Client::class  => ClientPolicy::class,
        Invoice::class => InvoicePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('is-admin', fn($user) => $user->role === 'admin');
    }
}
