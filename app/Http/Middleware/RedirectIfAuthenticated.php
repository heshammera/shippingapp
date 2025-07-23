<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user->role === 'moderator') {
                    return redirect('/shipments/create'); // ➔ يروح لصفحة إنشاء شحنة
                }

                if ($user->role === 'admin') {
                    return redirect('/dashboard'); // ➔ يروح للداشبورد
                }

                // لو الدور مش معروف مثلا
                return redirect('/'); 
            }
        }

        return $next($request);
    }
}
