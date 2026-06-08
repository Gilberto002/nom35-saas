<?php
// app/Http/Middleware/EnsureSuperAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $admin = $request->user('admin-api');

        if (!$admin) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        if (!$admin->isSuperAdmin()) {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        return $next($request);
    }
}
