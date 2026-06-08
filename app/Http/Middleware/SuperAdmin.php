<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class SuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json(['message' => 'Token requerido'], 401);
        }

        // Buscar el token en la BD manualmente
        $tokenRecord = PersonalAccessToken::findToken($bearerToken);

        if (!$tokenRecord) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        // Verificar que el tokenable sea AdminUser
        $admin = $tokenRecord->tokenable;

        if (!$admin || !($admin instanceof \App\Models\AdminUser)) {
            return response()->json(['message' => 'No es admin'], 403);
        }

        if (!$admin->isSuperAdmin()) {
            return response()->json(['message' => 'No es super admin'], 403);
        }

        // Inyectar el admin en el request para usarlo en los controladores
        $request->setUserResolver(fn() => $admin);

        return $next($request);
    }
}