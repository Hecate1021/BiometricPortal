<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. If not logged in, kick them to login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. If the user's role is in the allowed list, let them pass
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Fallback: If the route requires 'hr', let Admins pass too (using your model helper!)
        if (in_array('hr', $roles) && $user->isAdmin()) {
            return $next($request);
        }

        // 4. If they don't have permission, show a 403 error
        abort(403, 'Unauthorized Access. You do not have the required permissions.');
    }
}