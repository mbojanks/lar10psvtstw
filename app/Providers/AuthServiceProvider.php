<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        /** @var CachesRoutes $app */
        // if (! $this->app->routesAreCached()) {
                //Passport::Routes();
        // }

        Gate::define('view', function(User $user, $model) {
            //return false; za sada
            return $user->hasAccess("view_$model") || $user->hasAccess("edit_$model");
        });

        Gate::define('edit', function(User $user, $model) {
            //return false; za sada
            return $user->hasAccess("edit_$model");
        });

        Gate::define('permitted', function(User $user, $perm) {
            return $user->hasAccess($perm);
        });
    }
}
