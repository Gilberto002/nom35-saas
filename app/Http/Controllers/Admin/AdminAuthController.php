<?php
// app/Http/Controllers/Admin/AdminAuthController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $admin = AdminUser::where('email', strtolower(trim($request->email)))->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        if (!$admin->isSuperAdmin()) {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        // Borra tokens viejos para evitar acumulación
        $admin->tokens()->delete();

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'admin' => $admin->only('id', 'name', 'email', 'role'),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user('admin-api')?->currentAccessToken()?->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function me(Request $request)
    {
        $admin = $request->user('admin-api');

        if (!$admin) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        return response()->json(
            $admin->only('id', 'name', 'email', 'role')
        );
    }
}
