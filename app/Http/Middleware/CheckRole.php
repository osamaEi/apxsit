<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if (Auth::user()->role != $role) {
            // Redirect to appropriate dashboard based on user's role
            $user = Auth::user();
            switch ($user->role) {
                case 'Admin':
                    return redirect()->route('admin.dashboard');
                case 'Sales':
                    return redirect()->route('sales.dashboard');
                case 'Register':
                    return redirect()->route('register.dashboard');
                default:
                    return redirect()->route('employee.dashboard');
            }
        }

        return $next($request);
    }
}