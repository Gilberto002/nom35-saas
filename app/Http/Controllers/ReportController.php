<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\Area;
use App\Models\Result;
use App\Models\Evaluation;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{

public function index(Request $request)
{
    $companyId = Auth::user()->company_id;

    if (!$companyId) {
        return response()->json(['error' => 'Usuario sin empresa asignada'], 403);
    }

    $query = Result::with(['evaluation.employee.area'])
        ->whereHas('evaluation', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        });

    if ($request->area_id) {
        $query->whereHas('evaluation.employee', function ($q) use ($request) {
            $q->where('area_id', $request->area_id);
        });
    }

    if ($request->risk_level) {
        $query->where('risk_level', $request->risk_level);
    }

    if ($request->guide) {
        $query->whereHas('evaluation', function ($q) use ($request) {
            $q->where('guide', $request->guide);
        });
    }

    $results = $query->latest()->get()->map(function ($r) {
        $employee = $r->evaluation?->employee;

        return [
            'id'          => $r->id,
            'employee_id' => $employee?->id,
            'first_name'  => $employee?->first_name,
            'last_name'   => $employee?->last_name,
            'area_name'   => $employee?->area?->name ?? '—',
            'guide'       => $r->evaluation?->guide  ?? '—',
            'score'       => $r->score,
            'risk_level'  => $r->risk_level,
            'date'        => $r->created_at,
        ];
    });

    return response()->json($results);
}
    public function generate(Request $request)
    {
        $request->validate([
            'type'      => 'required|in:company,area,risk,individual',
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date',
        ]);

        $company   = Auth::user()->company;
        $dateFrom  = $request->date_from ? Carbon::parse($request->date_from)->startOfDay() : Carbon::now()->startOfYear();
        $dateTo    = $request->date_to   ? Carbon::parse($request->date_to)->endOfDay()     : Carbon::now()->endOfDay();
        $include   = json_decode($request->include ?? '{}', true);

        // ── Build query base ──
        $query = Result::query()
            ->with(['evaluation.employee.area', 'evaluation.questionnaire'])
            ->whereHas('evaluation.employee', fn($q) => $q->where('company_id', $company->id))
            ->whereBetween('created_at', [$dateFrom, $dateTo]);

        if ($request->area_id) {
            $query->whereHas('evaluation.employee', fn($q) => $q->where('area_id', $request->area_id));
        }
        if ($request->questionnaire_id) {
            $query->whereHas('evaluation', fn($q) => $q->where('questionnaire_id', $request->questionnaire_id));
        }
        if ($request->risk_level) {
            $query->where('risk_level', $request->risk_level);
        }
        if ($request->type === 'risk') {
            $query->whereIn('risk_level', ['Alto', 'Muy Alto']);
        }

        $results = $query->get();

        // ── Stats ──
        $totalEmployees  = Employee::where('company_id', $company->id)->count();
        $totalEvaluated  = $results->pluck('evaluation.employee_id')->unique()->count();
        $avgScore        = $results->avg('score') ? round($results->avg('score'), 1) : 0;

        $riskCounts = [
            'Bajo'     => $results->where('risk_level', 'Bajo')->count(),
            'Medio'    => $results->where('risk_level', 'Medio')->count(),
            'Alto'     => $results->where('risk_level', 'Alto')->count(),
            'Muy Alto' => $results->where('risk_level', 'Muy Alto')->count(),
        ];

        // ── Areas summary ──
        $areas = Area::where('company_id', $company->id)->get()->map(function ($area) use ($results) {
            $areaResults = $results->filter(
                fn($r) => $r->evaluation->employee->area_id === $area->id
            );
            return [
                'name'       => $area->name,
                'count'      => $areaResults->count(),
                'avg_score'  => $areaResults->count() ? round($areaResults->avg('score'), 1) : null,
                'risk_level' => $areaResults->count() ? $this->scoreToRisk($areaResults->avg('score')) : '—',
            ];
        })->sortByDesc('avg_score')->values();

        // ── Data for view ──
        $data = [
            'company'        => $company,
            'reportType'     => $request->type,
            'dateFrom'       => $dateFrom->format('d/m/Y'),
            'dateTo'         => $dateTo->format('d/m/Y'),
            'generatedAt'    => Carbon::now()->format('d/m/Y H:i'),
            'totalEmployees' => $totalEmployees,
            'totalEvaluated' => $totalEvaluated,
            'compliance'     => $totalEmployees ? round($totalEvaluated / $totalEmployees * 100) : 0,
            'avgScore'       => $avgScore,
            'riskCounts'     => $riskCounts,
            'areas'          => $areas,
            'results'        => $results,
            'include'        => $include,
        ];

        $pdf = Pdf::loadView('reports.nom035', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'  => 'sans-serif',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
            ]);

        $filename = "NOM035_{$request->type}_{$dateFrom->format('Ymd')}_{$dateTo->format('Ymd')}.pdf";

        return $pdf->download($filename);
    }

    private function scoreToRisk(float $score): string
    {
        if ($score < 20) return 'Bajo';
        if ($score < 45) return 'Medio';
        if ($score < 70) return 'Alto';
        return 'Muy Alto';
    }
}