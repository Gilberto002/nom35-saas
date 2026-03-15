<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CompanyAdminController extends Controller
{
    // Listar empresas
    public function index()
    {
        return Company::all();
    }

    // Crear empresa + admin
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        // Crear empresa
        $company = Company::create([
            'name' => $request->name,
            'rfc' => $request->rfc ?? null,
            'email' => $request->email,
            'phone' => $request->phone ?? null
        ]);

        // Crear usuario administrador de empresa
        $user = User::create([
            'name' => $request->admin_name ?? 'Admin '.$company->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $company->id
        ]);

        $user->assignRole('admin_empresa');

        return response()->json([
            'company' => $company,
            'admin_user' => $user
        ]);
    }

    // Ver empresa
    public function show($id)
    {
        return Company::findOrFail($id);
    }

    // Actualizar empresa
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());

        return $company;
    }

    // Eliminar empresa
    public function destroy($id)
    {
        Company::destroy($id);

        return response()->json([
            'message' => 'Empresa eliminada'
        ]);
    }
}