<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(160)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'))
                ->prefix('api/v1')
                ->group(base_path('routes/auth/api.php'));

            Route::middleware('api', 'auth:api')
                ->prefix('api/v1')
                ->group(base_path('routes/base/supply/api.php'))
                ->group(base_path('routes/base/sku/api.php'))
                ->group(base_path('routes/base/category/api.php'))
                ->group(base_path('routes/base/colors/api.php'))
                ->group(base_path('routes/base/coupon/api.php'))
                ->group(base_path('routes/base/designers/api.php'))
                ->group(base_path('routes/base/marketplace/api.php'))
                ->group(base_path('routes/base/payment_type/api.php'))
                ->group(base_path('routes/base/payment_type/api.php'))
                ->group(base_path('routes/base/product_image/api.php'))
                ->group(base_path('routes/base/product_tag/api.php'))
                ->group(base_path('routes/base/product/api.php'))
                ->group(base_path('routes/base/sizes/api.php'))
                ->group(base_path('routes/base/store/api.php'))
                ->group(base_path('routes/base/tags/api.php'))
                ->group(base_path('routes/base/item/api.php'))
                ->group(base_path('routes/base/sku/api.php'))
                ->group(base_path('routes/base/invoice/api.php'))
                ->group(base_path('routes/base/invoice_file/api.php'))
                ->group(base_path('routes/base/pricing_schemes/api.php'))
                ->group(base_path('routes/base/variant/api.php'))
                ->group(base_path('routes/base/characteristics/api.php'))
                ->group(base_path('routes/base/provider/api.php'))
                ->group(base_path('routes/base/country/api.php'))
                ->group(base_path('routes/base/supply_item/api.php'))
                ->group(base_path('routes/base/supply_transfer/api.php'))
                ->group(base_path('routes/base/notification/api.php'))
                ->group(base_path('routes/base/user_address/api.php'))
                ->group(base_path('routes/base/user/api.php'))
                ->group(base_path('routes/base/shipping_price/api.php'))
                ->group(base_path('routes/base/event_types/api.php'))
                ->group(base_path('routes/base/events/api.php'))
                ->group(base_path('routes/marketplace/rent_detail_marketplace/api.php'))
                ->group(base_path('routes/marketplace/order_marketplace/api.php'))
                ->group(base_path('routes/examples/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/auth/web.php'))
                ->group(base_path('routes/base/supply/web.php'))
                ->group(base_path('routes/base/supply_item/web.php'))
                ->group(base_path('routes/base/supply_transfer/web.php'));

            Route::middleware('web', 'verified', 'permission')
                ->group(base_path('routes/home/web.php'))
                ->group(base_path('routes/auth/roles/web.php'))
                ->group(base_path('routes/base/supply/report/web.php'))
                ->group(base_path('routes/base/supply/web.php'))
                ->group(base_path('routes/base/sku/web.php'))
                ->group(base_path('routes/base/category/web.php'))
                ->group(base_path('routes/base/colors/web.php'))
                ->group(base_path('routes/base/characteristics/web.php'))
                ->group(base_path('routes/base/designers/web.php'))
                ->group(base_path('routes/base/discounts/web.php'))
                ->group(base_path('routes/base/country/web.php'))
                ->group(base_path('routes/base/marketplace/web.php'))
                ->group(base_path('routes/base/payment_type/web.php'))
                ->group(base_path('routes/base/payment_type/web.php'))
                ->group(base_path('routes/base/product_image/web.php'))
                ->group(base_path('routes/base/product_tag/web.php'))
                ->group(base_path('routes/base/product/web.php'))
                ->group(base_path('routes/base/sizes/web.php'))
                ->group(base_path('routes/base/store/web.php'))
                ->group(base_path('routes/base/tags/web.php'))
                ->group(base_path('routes/base/item/report/web.php'))
                ->group(base_path('routes/base/item/web.php'))
                ->group(base_path('routes/base/variant/web.php'))
                ->group(base_path('routes/base/provider/web.php'))
                ->group(base_path('routes/base/sku/web.php'))
                ->group(base_path('routes/base/invoice/web.php'))
                ->group(base_path('routes/base/invoice_file/web.php'))
                ->group(base_path('routes/base/pricing_schemes/web.php'))
                ->group(base_path('routes/base/notification/web.php'))
                ->group(base_path('routes/base/user_address/web.php'))
                ->group(base_path('routes/base/temporary_invitation/web.php'))
                ->group(base_path('routes/base/user/web.php'))
                ->group(base_path('routes/base/coupon/web.php'))
                ->group(base_path('routes/base/shipping_price/web.php'))
                ->group(base_path('routes/base/event_types/web.php'))
                ->group(base_path('routes/base/events/web.php'))
                ->group(base_path('routes/marketplace/order_marketplace/income/web.php'))
                ->group(base_path('routes/marketplace/order_marketplace/report/web.php'))
                ->group(base_path('routes/marketplace/payment_order_marketplace/web.php'))
                ->group(base_path('routes/marketplace/item_order_marketplace/web.php'))
                ->group(base_path('routes/marketplace/rent_detail_marketplace/web.php'))
                ->group(base_path('routes/examples/web.php'))
                ->group(base_path('routes/examples/web.php'))
                ->group(base_path('routes/web.php'))
                ->group(base_path('routes/web.php'));

            // Permissions defined specifically
            Route::middleware('web', 'verified')
                ->group(base_path('routes/marketplace/order_marketplace/web.php'));
        });
    }
}
