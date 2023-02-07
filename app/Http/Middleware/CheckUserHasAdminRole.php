<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserHasAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }


        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'access denied'
            ]);
        } else {
            return redirect()->route('warehouses.index');
        }
    }
}
