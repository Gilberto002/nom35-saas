<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Company;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        return Area::withCount('employees')->orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['company_id'] = $this->resolveCompanyId($request);

        $area = Area::create($validated);

        return response()->json($area, 201);
    }

    public function show(Area $area)
    {
        return $area->loadCount('employees');
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $area->update($validated);

        return response()->json($area->fresh()->loadCount('employees'));
    }

    public function destroy(Area $area)
    {
        $area->delete();

        return response()->json(['message' => 'Área eliminada']);
    }

    private function resolveCompanyId(Request $request): int
    {
        $userCompanyId = optional($request->user())->company_id;

        if ($userCompanyId) {
            return $userCompanyId;
        }

        return Company::query()->firstOrCreate(
            ['name' => 'Empresa Demo'],
            ['active' => true]
        )->id;
    }
}
