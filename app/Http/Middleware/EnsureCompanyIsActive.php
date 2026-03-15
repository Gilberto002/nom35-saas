<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'No autenticado'], 401)
                : redirect()->route('login');
        }

        // Superadmin o usuarios sin empresa aún: no bloquear aquí.
        if (empty($user->company_id)) {
            return $next($request);
        }

        $company = Company::find($user->company_id);

        if (!$company || !$company->active) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Empresa inactiva'], 403)
                : redirect('/billing');
        }

        return $next($request);
    }
}
