<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
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
    public const HOME = '/welcome';

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

            Route::middleware(['web'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/web.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','role:admin','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/accounts.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','checkProductsQuantity','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/products.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/users.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','role:admin','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/roles.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','role:admin','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/permissions.php'));


            Route::middleware(['web','activated','localization','saveRequestHistory','role:admin','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/discoverActions.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','role:admin','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/archiveBalances.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','role:admin','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/pounds.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','role:admin','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/activityLog.php'));

            Route::middleware(['web','activated','localization',"role:admin",'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/admin/backups.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/config.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/categories.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/stores.php'));

            Route::middleware(['web','activated','localization','saveRequestHistory','checkProductsQuantity','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/invoices.php'));

           Route::middleware(['web','activated','localization','auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/notifications.php'));
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
