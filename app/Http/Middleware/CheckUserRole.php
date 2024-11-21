<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::user();

        // Allow system admin to bypass company restriction
        if ($role !== 'system admin' && !$user->company_id) {
            abort(403, 'Unauthorized access');
        }

        // Redirect if the user does not match the required role
        if ($user->role !== $role) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
