<?php

namespace App\Http\Middleware;

use App\Traits\Helpers\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ValidateEmployee
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::user()->employeeDetail()->exists()) {
            return $this->makeResponse([
                'message' => 'Only employees can see this page',
                'success' => false,
                'status' => Response::HTTP_UNAUTHORIZED,
                'exception' => new RuntimeException('Only employees can see this page'),
            ]);
        }

        return $next($request);
    }
}
