<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required'
    ]);

    $credentials = [
        'email' => strtolower(trim((string) $request->email)),
        'password' => (string) $request->password,
    ];

    if (!Auth::attempt($credentials, (bool) $request->remember)) {
        return response()->json([
            'message' => 'Credenciales incorrectas'
        ], 401);
    }

    // /api/login puede llegar sin middleware de sesión.
    if ($request->hasSession()) {
        $request->session()->regenerate();
    }

    $user  = Auth::user();
    $token = $user->createToken('nom035-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user'  => $user
    ]);
}


   public function logout(Request $request)
{
    $user = $request->user();
    $token = $user?->currentAccessToken();

    if ($token) {
        $token->delete();
    }

    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}


    public function user(Request $request)
    {

        return response()->json($request->user());

    }

    public function register(Request $request)
{

    $request->validate([
        'company_name'  => 'required|string|max:255',
        'company_email' => 'required|email|unique:companies,email',
        'admin_email'   => 'required|email|unique:users,email',
        'admin_name'    => 'required|string',
        'password'      => 'required|min:8|confirmed',
        'plan_id'       => 'required|exists:plans,id',
    ]);

    /*
    |--------------------------------
    | Crear empresa
    |--------------------------------
    */

    $company = Company::create([
        'name' => $request->company_name,
        'email' => $request->company_email,
        'active' => 1
    ]);

    /*
    |--------------------------------
    | Crear usuario administrador
    |--------------------------------
    */

    $user = User::create([
        'name' => $request->admin_name,
        'email' => $request->admin_email,
        'password' => Hash::make($request->password),
        'company_id' => $company->id
    ]);

    /*
    |--------------------------------
    | Crear suscripción
    |--------------------------------
    */

    Subscription::create([
        'company_id' => $company->id,
        'plan_id' => $request->plan_id,
        'status' => 'trial',
        'trial_ends_at' => now()->addDays(30),
        'current_period_start' => now(),
        'current_period_end' => now()->addDays(30),
    ]);

    Auth::login($user);

    return response()->json([
        'ok' => true,
        'company' => $company,
        'user' => $user
    ]);

}

}
