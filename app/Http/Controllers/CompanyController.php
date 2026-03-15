<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{

    public function index()
    {
        return Company::all();
    }

    public function store(Request $request)
    {
        $company = Company::create($request->all());

        return response()->json($company);
    }

    public function show($id)
    {
        return Company::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());

        return response()->json($company);
    }

    public function destroy($id)
    {
        Company::destroy($id);

        return response()->json([
            'message' => 'Empresa eliminada'
        ]);
    }
}