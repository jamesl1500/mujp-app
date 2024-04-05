<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isMember', function ($user){
            return $user->hasRole('member') == true;
        });

        Gate::define('isDataEntry', function ($user){
            return $user->hasRole('data-entry') == true;
        });

        Gate::define('isAdmin', function ($user){
            return $user->hasRole('admin') == true;
        });
    }
}
