<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->status) {
            // Perform logout
            Auth::logout();

            // Invalidate the user's session and regenerate the token
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // // Redirect to the login page with an error message
            // return redirect()->route('login')->with('status', 'Your account has been deactivated. Please log in again.');
            flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->error('Your account has been deactivated.');

            // Redirect the user to the login page or another page
            return redirect()->route('login');
        }

        return $next($request);
    }
}
