<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Ensure the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Safely grab the user's role
        $userRole = strtolower(trim(Auth::user()->role));

        // 3. Check if their role is inside the allowed $roles array
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // 4. If they don't have permission, bounce them back to the employee desk
        return redirect()->route('employee.forms.index')->with('error', 'You do not have permission to access that area.');
    }
}