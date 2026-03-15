<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Questionnaire;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        Questionnaire::create([
            'name' => 'Guía I - Eventos Traumáticos Severos',
            'type' => 'guia1',
            'description' => 'Identificación de trabajadores que fueron sujetos a acontecimientos traumáticos severos'
        ]);

        Questionnaire::create([
            'name' => 'Guía II - Factores de Riesgo Psicosocial',
            'type' => 'guia2',
            'description' => 'Identificación y análisis de factores de riesgo psicosocial'
        ]);

        Questionnaire::create([
            'name' => 'Guía III - Entorno Organizacional',
            'type' => 'guia3',
            'description' => 'Identificación y análisis del entorno organizacional'
        ]);
    }
}