<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Evaluation;
use App\Models\Employee;
use App\Models\Answer;
use App\Models\Result;
use App\Models\Question;

class EvaluationController extends Controller
{
    // Ahora recibe todo por body, no por URL
    public function start(Request $request)
    {
        $request->validate([
            'employee_id'      => 'required|exists:employees,id',
            'questionnaire_id' => 'required|integer',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        // Mapear questionnaire_id → nombre de guía
        $guideMap = [
            1 => 'Guía de referencia I',
            2 => 'Guía de referencia II',
            3 => 'Guía de referencia III',
        ];
        $guide = $guideMap[$request->questionnaire_id] ?? 'Guía ' . $request->questionnaire_id;

        $evaluation = Evaluation::create([
            'company_id'  => Auth::user()->company_id,
            'employee_id' => $employee->id,
            'guide'       => $guide,
            'status'      => 'in_progress',
        ]);

        return response()->json([
            'message'       => 'Evaluación iniciada',
            'evaluation_id' => $evaluation->id,
            'evaluation'    => $evaluation,
        ]);
    }

    // Acepta array de respuestas de una vez
    public function answer(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:evaluations,id',
            'answers'       => 'required|array',
            'answers.*.question_id' => 'required|integer',
            'answers.*.value'       => 'required|integer|min:0|max:4',
        ]);

        foreach ($request->answers as $ans) {
            Answer::updateOrCreate(
                [
                    'evaluation_id' => $request->evaluation_id,
                    'question_id'   => $ans['question_id'],
                ],
                ['value' => $ans['value']]
            );
        }

        return response()->json(['message' => 'Respuestas guardadas']);
    }

    // Recibe evaluation_id por body
    public function finish(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:evaluations,id',
        ]);

        $evaluationId = $request->evaluation_id;

        $answers = Answer::where('evaluation_id', $evaluationId)->get();
        $score   = $answers->sum('value');

        if      ($score < 20) $risk = 'Bajo';
        elseif  ($score < 45) $risk = 'Medio';
        elseif  ($score < 70) $risk = 'Alto';
        else                  $risk = 'Muy Alto';

        Result::create([
            'evaluation_id' => $evaluationId,
            'score'         => $score,
            'risk_level'    => $risk,
        ]);

        Evaluation::where('id', $evaluationId)
            ->update(['status' => 'completed']);

        return response()->json([
            'score'      => $score,
            'risk_level' => $risk,
        ]);
    }

    public function questions($questionnaire)
    {
        // questionnaire es el ID numérico (1, 2 o 3)
        $questions = Question::where('questionnaire_id', $questionnaire)->get();
        return response()->json($questions);
    }
}