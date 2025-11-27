<?php

namespace App\Providers;

use App\Enums\Auth\PermissionTypes;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\{
    Collection,
    ServiceProvider,
    Str,
};
use Illuminate\Support\Facades\{
    Blade,
    Route,
    View,
};
use App\Models\Base\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        setlocale(LC_TIME, 'spanish');

        Paginator::useBootstrap();

        View::composer('layouts.header', function ($view) {
            $notifications = $last24Notifications = Notification::where('date', '>=', now()->subDay())->take(10)->get()->reverse();
            $last24Notifications = Notification::where('date', '>=', now()->subDay())->get();
            $view->with([
                'notifications' => $notifications,
                'last24Notifications' => $last24Notifications
            ]);
        });

        Blade::directive('permission', function ($expression) {
            $elements = explode(',', $expression, 2);

            // Default defined module and permissions (manually)
            $module = $elements[0];
            $permissions = $elements[1] ?? $elements[0];

            if (Str::of($module)->startsWith(explode('\\', PermissionTypes::class)[3])) { // Define module automatically
                $route = explode('.', Route::currentRouteName());

                $module = '\'' . $route[min(1, count($route) - 1)] . '\'';

                $permissions = $expression;
            }

            return "<?php if (Auth::check() && (is_null($module) || Auth::user()->hasAnyPermission($module, $permissions))): ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });

        Collection::macro('clone', function () {
            return clone $this;
        });

        Collection::macro('paginate', function ($perPage = null, $page = null, $pageName = 'page') {
            $perPage ??= 15;
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        // Override de macros para convertir sum a float
        // QueryBuilder::macro('sum', function ($column) {
        //     $value = $this->aggregate('sum', [$column]);
        //     return $value !== null ? (float)$value : 0.0;
        // });

        EloquentBuilder::macro('sum', function ($column) {
            $value = $this->toBase()->sum($column);
            return (float)$value;
        });

        // Relation::macro('sum', function ($column) {
        //     $value = $this->getBaseQuery()->sum($column);
        //     return (float)$value;
        // });
    }
}
