<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Company;

class EmployeeController extends Controller
{

    public function index()
    {
        return Employee::with('area')
            ->withCount('evaluations')
            ->orderBy('first_name')
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        $data['company_id'] = $request->user()?->company_id
            ?? Company::query()->firstOrCreate(['name' => 'Empresa Demo'], ['active' => true])->id;

        $employee = Employee::create($data);

        return response()->json($employee);
    }

    public function show($id)
    {
        return Employee::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'area_id' => 'nullable|exists:areas,id',
        ]);
        $employee->update($data);

        return $employee;
    }

    public function destroy($id)
    {
        Employee::destroy($id);

        return response()->json([
            'message'=>'Empleado eliminado'
        ]);
    }
}
