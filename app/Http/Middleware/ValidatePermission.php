<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\Base\Permission\NeedsPermissions;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    Log,
    Route,
};
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ValidatePermission
{
    use NeedsPermissions;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param string $permissions List of minimum permissions to access.  
     * Should be composed by chars c(create), r(read), u(update)
     * @param string $module Modules key, either name or id
     */
    public function handle(Request $request, Closure $next, $permissions = null, $module = null): Response
    {
        if (!Auth::check()) {
            Log::channel('warning')->warning('Tried to access a page without login');

            return redirect()->route('login')->withErrors(['alert' => 'Por favor, inicie sesión para poder ver esta página']);
        } else if (!Auth::user()->email_verified_at) { // If user has not verified
            Log::channel('warning')->warning('Tried to access a page with a non verified account');

            return redirect()->route('verification.notice')->withErrors(['alert' => 'Por favor, verifique su cuenta para poder ver esta página']);
        }

        $permissions = $permissions ?
            $this->getPermissionsFromAliases($permissions) :
            $this->getDefaultPermissions(Route::currentRouteName() ?? '');

        $allowed = $this->forceAllowed(Auth::user(), $request) || $this->userIsAllowed(Auth::user(), $module, $permissions);

        if (!$allowed) {
            abort(403, 'You have no access to this page');
        }

        return $next($request);
    }

    private function userIsAllowed(User $user, string $module = null, array $permissions = [])
    {
        $module ??= Str::of(Route::currentRouteName())->betweenFirst('.', '.')->plural()->slug();

        try {
            return $user->hasAnyPermission($module, ...$permissions);
        } catch (Exception $ex) {
        }

        return false;
    }

    private function forceAllowed(User $user, Request $request)
    {
        $forceAllowed = false;

        $forceAllowed = $forceAllowed || in_array($request->fullUrl(), [
            route('base.user.show', $user->id),
            route('base.user.edit.view', $user->id),
            route('base.user.addresses', $user->id),
            route('base.user.edit_email', $user->id),
            route('base.user.update_email', $user->id),
            route('base.user.edit_password', $user->id),
            route('base.user.update_password', $user->id),
        ]);

        return $forceAllowed;
    }
}
