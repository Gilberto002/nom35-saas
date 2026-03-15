<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\Result;
use App\Models\Area;
use Inertia\Inertia;

class DashboardController extends Controller
{
      public function index()
    {
        $employees = Employee::count();
        $evaluationsCompleted = Evaluation::where('status','completed')->count();

        $riskLow = Result::where('risk_level','Bajo')->count();
        $riskMedium = Result::where('risk_level','Medio')->count();
        $riskHigh = Result::where('risk_level','Alto')->count();
        $riskVeryHigh = Result::where('risk_level','Muy Alto')->count();

        $areas = Area::withCount('employees')->get();

        return response()->json([
            'employees_total' => $employees,
            'evaluations_completed' => $evaluationsCompleted,
            'risk_levels' => [
                'low' => $riskLow,
                'medium' => $riskMedium,
                'high' => $riskHigh,
                'very_high' => $riskVeryHigh
            ],
            'areas' => $areas
        ]);
    }


    public function panel()
    {
        $employees = Employee::count();
        $evaluations = Evaluation::where('status','completed')->count();

        $low = Result::where('risk_level','Bajo')->count();
        $medium = Result::where('risk_level','Medio')->count();
        $high = Result::where('risk_level','Alto')->count();
        $veryHigh = Result::where('risk_level','Muy Alto')->count();

        return Inertia::render('Dashboard',[
            'data' => [
                'employees_total'=>$employees,
                'evaluations_completed'=>$evaluations,
                'risk_levels'=>[
                    'low'=>$low,
                    'medium'=>$medium,
                    'high'=>$high,
                    'very_high'=>$veryHigh
                ]
            ]
        ]);
    }
}