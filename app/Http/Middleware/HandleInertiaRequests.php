<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'layouts.inertia-app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'authUser' => Auth::user()?->append('permissions'),
            'appName' => config('app.name'),
            'baseUrl' => config('app.url'),
            'flash' => [
                'success' => fn () => Session::get('success'),
                'error' => fn () => Session::get('error'),
                'data' => fn () => Session::get('data'),
                'errors' => session('errors')?->getBag('default')->getMessages() ?? (object) [],
            ],
            'usersResult' => fn () => Session::get('usersResult'),
            'ordersResult' => Session::get('ordersResult'),
        ]);
    }
}
