<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/web.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/accounts.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/products.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/posts.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/users.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/roles.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/permissions.php'));


            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/discoverActions.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/archiveBalances.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/config.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/pounds.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/activityLog.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/comments.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/categories.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/stores.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web/invoices.php'));

        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
