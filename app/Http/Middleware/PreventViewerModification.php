<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventViewerModification
{
public function handle(Request $request, Closure $next): Response
{
    $user = auth()->user();

    // لو المستخدم مش Viewer، كمل عادي
    if (!$user || $user->roles->first()?->name !== 'viewer') {
        return $next($request);
    }

    // لو Viewer وبيستخدم PUT لتحديث حالة الشحنة، اسمح له
    if ($request->isMethod('put') && $request->routeIs('shipments.update-status')) {
        return $next($request);
    }

    // امنع أي method غير GET
if ($request->is('login')) {
    return $next($request);
}

    // السماح بـ GET فقط
    return $next($request);
}


}
