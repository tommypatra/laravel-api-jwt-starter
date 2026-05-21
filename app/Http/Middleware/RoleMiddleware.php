<?php

namespace App\Http\Middleware;

use App\Models\RoleUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $hasRole = RoleUser::where('user_id', $user->id)
            ->whereIn('role_id', $roles)
            ->exists();

        if (! $hasRole) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses',
            ], 403);
        }

        return $next($request);
    }
}
