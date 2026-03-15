<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Questionnaire;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $guia1 = Questionnaire::where('type', 'guia1')->first();
        $guia2 = Questionnaire::where('type', 'guia2')->first();
        $guia3 = Questionnaire::where('type', 'guia3')->first();

        /*
        ========================
        GUÍA I
        ========================
        */

        $questionsGuia1 = [
            "¿Ha presenciado o sufrido algún accidente que haya puesto en riesgo su vida?",
            "¿Ha sido víctima de asalto, secuestro o violencia física en su trabajo?",
            "¿Ha presenciado actos violentos graves en su lugar de trabajo?",
            "¿Ha sufrido amenazas graves en su trabajo?",
            "¿Ha estado expuesto a situaciones traumáticas severas en el trabajo?"
        ];

        foreach ($questionsGuia1 as $index => $question) {
            Question::create([
                'questionnaire_id' => $guia1->id,
                'question_text' => $question,
                'order' => $index + 1
            ]);
        }

        /*
        ========================
        GUÍA II (ejemplo parcial)
        ========================
        */

        $questionsGuia2 = [
            "Tengo que realizar mi trabajo muy rápido",
            "Mi trabajo exige que esté muy concentrado",
            "Mi trabajo requiere que atienda varios asuntos al mismo tiempo",
            "Tengo que trabajar bajo presión de tiempo",
            "El trabajo que realizo requiere gran esfuerzo mental"
        ];

        foreach ($questionsGuia2 as $index => $question) {
            Question::create([
                'questionnaire_id' => $guia2->id,
                'question_text' => $question,
                'order' => $index + 1
            ]);
        }

        /*
        ========================
        GUÍA III (ejemplo parcial)
        ========================
        */

        $questionsGuia3 = [
            "En mi trabajo puedo expresar mis opiniones",
            "En mi trabajo recibo apoyo de mis compañeros",
            "En mi trabajo existe buena comunicación",
            "En mi trabajo se reconocen los logros",
            "En mi trabajo existe un ambiente de respeto"
        ];

        foreach ($questionsGuia3 as $index => $question) {
            Question::create([
                'questionnaire_id' => $guia3->id,
                'question_text' => $question,
                'order' => $index + 1
            ]);
        }
    }
}