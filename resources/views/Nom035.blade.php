<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: sans-serif; font-size: 10px; color: #1E293B; background: white; }

  /* ── Page layout ── */
  .page { padding: 32px 36px; }

  /* ── Header ── */
  .header { display: table; width: 100%; margin-bottom: 20px; }
  .header-left  { display: table-cell; vertical-align: middle; }
  .header-right { display: table-cell; vertical-align: middle; text-align: right; width: 160px; }

  .logo-box {
    display: inline-block;
    background: #0EA5E9; color: white;
    font-size: 11px; font-weight: bold;
    padding: 6px 12px; border-radius: 6px;
    letter-spacing: 1px; margin-bottom: 4px;
  }
  .company-name { font-size: 16px; font-weight: bold; color: #0C4A6E; }
  .report-sub   { font-size: 9px; color: #64748B; margin-top: 2px; }

  .stps-badge {
    border: 1.5px solid #0EA5E9; border-radius: 6px;
    padding: 6px 10px; display: inline-block; text-align: center;
  }
  .stps-badge .stps-top { font-size: 7px; color: #0369A1; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
  .stps-badge .stps-main { font-size: 11px; font-weight: bold; color: #0C4A6E; }

  .divider { border: none; border-top: 2px solid #0EA5E9; margin: 12px 0; }
  .divider-thin { border: none; border-top: 1px solid #E2E8F0; margin: 10px 0; }

  /* ── Report title bar ── */
  .title-bar {
    background: #0C4A6E; color: white;
    padding: 10px 16px; border-radius: 8px;
    margin-bottom: 16px;
    display: table; width: 100%;
  }
  .title-bar-left  { display: table-cell; vertical-align: middle; }
  .title-bar-right { display: table-cell; vertical-align: middle; text-align: right; }
  .title-bar h1 { font-size: 14px; font-weight: bold; }
  .title-bar .period { font-size: 9px; color: rgba(255,255,255,0.7); margin-top: 2px; }
  .title-bar .gen-date { font-size: 9px; color: rgba(255,255,255,0.7); }

  /* ── Info row ── */
  .info-row { display: table; width: 100%; margin-bottom: 16px; }
  .info-cell { display: table-cell; width: 25%; padding: 10px 12px; background: #F8FAFC; border-radius: 6px; }
  .info-cell + .info-cell { margin-left: 8px; }
  .info-label { font-size: 8px; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
  .info-value { font-size: 16px; font-weight: bold; color: #0C4A6E; }
  .info-sub   { font-size: 8px; color: #64748B; margin-top: 2px; }

  /* ── Section title ── */
  .section-title {
    font-size: 11px; font-weight: bold; color: #0C4A6E;
    border-left: 3px solid #0EA5E9;
    padding-left: 8px; margin-bottom: 10px;
  }

  /* ── Risk distribution ── */
  .risk-grid { display: table; width: 100%; margin-bottom: 16px; }
  .risk-cell { display: table-cell; width: 25%; text-align: center; padding: 12px 8px; border-radius: 8px; }
  .risk-cell + .risk-cell { padding-left: 12px; }

  .risk-bajo     { background: #F0FDF4; }
  .risk-medio    { background: #FFFBEB; }
  .risk-alto     { background: #FEF2F2; }
  .risk-muyalto  { background: #F5F3FF; }

  .risk-num   { font-size: 22px; font-weight: bold; }
  .risk-label { font-size: 9px; font-weight: bold; margin-top: 3px; }
  .risk-pct   { font-size: 8px; margin-top: 2px; }

  .risk-bajo     .risk-num, .risk-bajo     .risk-label { color: #16A34A; }
  .risk-medio    .risk-num, .risk-medio    .risk-label { color: #D97706; }
  .risk-alto     .risk-num, .risk-alto     .risk-label { color: #DC2626; }
  .risk-muyalto  .risk-num, .risk-muyalto  .risk-label { color: #7C3AED; }
  .risk-pct { color: #94A3B8; }

  /* ── Bar chart ── */
  .bar-row { display: table; width: 100%; margin-bottom: 6px; }
  .bar-label-cell { display: table-cell; width: 120px; font-size: 9px; color: #475569; vertical-align: middle; }
  .bar-track-cell { display: table-cell; vertical-align: middle; padding: 0 8px; }
  .bar-track { background: #F1F5F9; border-radius: 4px; height: 8px; }
  .bar-fill  { border-radius: 4px; height: 8px; }
  .bar-meta-cell  { display: table-cell; width: 80px; font-size: 9px; vertical-align: middle; text-align: right; }
  .bar-score { font-weight: bold; color: #0C4A6E; }
  .risk-chip-inline {
    display: inline-block; font-size: 7px; font-weight: bold;
    padding: 2px 6px; border-radius: 100px; margin-left: 4px;
  }
  .chip-bajo     { background: #F0FDF4; color: #16A34A; }
  .chip-medio    { background: #FFFBEB; color: #D97706; }
  .chip-alto     { background: #FEF2F2; color: #DC2626; }
  .chip-muyalto  { background: #F5F3FF; color: #7C3AED; }

  /* ── Table ── */
  .data-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
  .data-table th {
    background: #F8FAFC; padding: 7px 10px; text-align: left;
    font-size: 8px; font-weight: bold; color: #64748B;
    text-transform: uppercase; letter-spacing: 0.5px;
    border-bottom: 1.5px solid #E2E8F0;
  }
  .data-table td {
    padding: 7px 10px; font-size: 9px; color: #475569;
    border-bottom: 1px solid #F8FAFC;
  }
  .data-table tr:last-child td { border-bottom: none; }
  .data-table tr:nth-child(even) td { background: #FAFCFF; }

  .emp-name { font-weight: bold; color: #1E293B; }
  .emp-email { font-size: 8px; color: #94A3B8; }

  .badge {
    display: inline-block; font-size: 7px; font-weight: bold;
    padding: 2px 7px; border-radius: 100px;
  }

  /* ── Recommendations ── */
  .rec-box { padding: 12px 14px; border-radius: 8px; margin-bottom: 8px; }
  .rec-box.bajo     { background: #F0FDF4; border-left: 3px solid #22C55E; }
  .rec-box.medio    { background: #FFFBEB; border-left: 3px solid #F59E0B; }
  .rec-box.alto     { background: #FEF2F2; border-left: 3px solid #EF4444; }
  .rec-box.muyalto  { background: #F5F3FF; border-left: 3px solid #7C3AED; }
  .rec-title { font-size: 9px; font-weight: bold; margin-bottom: 4px; }
  .rec-text  { font-size: 8.5px; color: #475569; line-height: 1.6; }

  /* ── Footer ── */
  .footer {
    position: fixed; bottom: 20px; left: 36px; right: 36px;
    display: table; width: 100%;
    border-top: 1px solid #E2E8F0; padding-top: 8px;
  }
  .footer-left  { display: table-cell; font-size: 8px; color: #94A3B8; }
  .footer-right { display: table-cell; text-align: right; font-size: 8px; color: #94A3B8; }

  /* ── Compliance ring (simple bar version for PDF) ── */
  .compliance-bar-wrap { margin-bottom: 16px; }
  .compliance-label { font-size: 9px; color: #64748B; margin-bottom: 4px; }
  .compliance-track { background: #F1F5F9; border-radius: 4px; height: 12px; }
  .compliance-fill  { background: linear-gradient(90deg, #0369A1, #0EA5E9); border-radius: 4px; height: 12px; }
  .compliance-pct   { font-size: 11px; font-weight: bold; color: #0369A1; text-align: right; margin-top: 3px; }

  .page-break { page-break-after: always; }
  .no-break   { page-break-inside: avoid; }
</style>
</head>
<body>

<div class="page">

  <!-- Footer (fixed, repeats on every page) -->
  <div class="footer">
    <div class="footer-left">
      NOM-035-STPS-2018 · {{ $company->name }} · Generado: {{ $generatedAt }}
    </div>
    <div class="footer-right">Documento confidencial — uso interno</div>
  </div>

  <!-- ══ HEADER ══ -->
  <div class="header">
    <div class="header-left">
      <div class="logo-box">NOM-035</div>
      <div class="company-name">{{ $company->name }}</div>
      <div class="report-sub">RFC: {{ $company->rfc ?? 'N/A' }} · {{ $company->email }}</div>
    </div>
    <div class="header-right">
      <div class="stps-badge">
        <div class="stps-top">Cumplimiento</div>
        <div class="stps-main">NOM-035-STPS-2018</div>
      </div>
    </div>
  </div>

  <hr class="divider"/>

  <!-- ══ TITLE BAR ══ -->
  <div class="title-bar">
    <div class="title-bar-left">
      <h1>
        @if($reportType === 'company') Reporte Organizacional de Riesgo Psicosocial
        @elseif($reportType === 'area') Reporte por Área — Riesgo Psicosocial
        @elseif($reportType === 'risk') Reporte de Empleados con Riesgo Alto
        @else Reporte Individual de Evaluación
        @endif
      </h1>
      <div class="period">Período: {{ $dateFrom }} al {{ $dateTo }}</div>
    </div>
    <div class="title-bar-right">
      <div class="gen-date">Generado el {{ $generatedAt }}</div>
    </div>
  </div>

  @if(!empty($include['summary']))
  <!-- ══ RESUMEN EJECUTIVO ══ -->
  <div class="section-title">Resumen Ejecutivo</div>

  <!-- KPIs -->
  <div class="info-row">
    <div class="info-cell">
      <div class="info-label">Total Empleados</div>
      <div class="info-value">{{ $totalEmployees }}</div>
      <div class="info-sub">En la organización</div>
    </div>
    <div class="info-cell" style="padding-left:8px;">
      <div class="info-label">Evaluados</div>
      <div class="info-value">{{ $totalEvaluated }}</div>
      <div class="info-sub">{{ $compliance }}% de cumplimiento</div>
    </div>
    <div class="info-cell" style="padding-left:8px;">
      <div class="info-label">Puntuación Promedio</div>
      <div class="info-value">{{ $avgScore }}</div>
      <div class="info-sub">Escala 0–100</div>
    </div>
    <div class="info-cell" style="padding-left:8px;">
      <div class="info-label">Total Evaluaciones</div>
      <div class="info-value">{{ $results->count() }}</div>
      <div class="info-sub">En el período</div>
    </div>
  </div>

  <!-- Compliance bar -->
  <div class="compliance-bar-wrap no-break">
    <div class="compliance-label">Avance de cumplimiento NOM-035</div>
    <div class="compliance-track">
      <div class="compliance-fill" style="width: {{ $compliance }}%;"></div>
    </div>
    <div class="compliance-pct">{{ $compliance }}% completado</div>
  </div>
  @endif

  @if(!empty($include['risk_chart']))
  <!-- ══ DISTRIBUCIÓN DE RIESGO ══ -->
  <hr class="divider-thin"/>
  <div class="section-title">Distribución de Niveles de Riesgo</div>

  @php $total = $results->count() ?: 1; @endphp
  <div class="risk-grid no-break">
    <div class="risk-cell risk-bajo">
      <div class="risk-num">{{ $riskCounts['Bajo'] }}</div>
      <div class="risk-label">BAJO</div>
      <div class="risk-pct">{{ round($riskCounts['Bajo'] / $total * 100) }}% del total</div>
    </div>
    <div class="risk-cell risk-medio" style="padding-left:8px;">
      <div class="risk-num">{{ $riskCounts['Medio'] }}</div>
      <div class="risk-label">MEDIO</div>
      <div class="risk-pct">{{ round($riskCounts['Medio'] / $total * 100) }}% del total</div>
    </div>
    <div class="risk-cell risk-alto" style="padding-left:8px;">
      <div class="risk-num">{{ $riskCounts['Alto'] }}</div>
      <div class="risk-label">ALTO</div>
      <div class="risk-pct">{{ round($riskCounts['Alto'] / $total * 100) }}% del total</div>
    </div>
    <div class="risk-cell risk-muyalto" style="padding-left:8px;">
      <div class="risk-num">{{ $riskCounts['Muy Alto'] }}</div>
      <div class="risk-label">MUY ALTO</div>
      <div class="risk-pct">{{ round($riskCounts['Muy Alto'] / $total * 100) }}% del total</div>
    </div>
  </div>
  @endif

  @if(!empty($include['area_table']) && $areas->count())
  <!-- ══ RESULTADOS POR ÁREA ══ -->
  <hr class="divider-thin"/>
  <div class="section-title">Resultados por Área</div>

  @foreach($areas as $area)
    @if($area['avg_score'] !== null)
    <div class="bar-row no-break">
      <div class="bar-label-cell">{{ $area['name'] }}</div>
      <div class="bar-track-cell">
        <div class="bar-track">
          <div class="bar-fill" style="width: {{ min($area['avg_score'], 100) }}%; background:
            @if($area['risk_level'] === 'Bajo') #22C55E
            @elseif($area['risk_level'] === 'Medio') #F59E0B
            @elseif($area['risk_level'] === 'Alto') #EF4444
            @else #7C3AED
            @endif;"></div>
        </div>
      </div>
      <div class="bar-meta-cell">
        <span class="bar-score">{{ $area['avg_score'] }}</span>
        <span class="risk-chip-inline
          @if($area['risk_level'] === 'Bajo') chip-bajo
          @elseif($area['risk_level'] === 'Medio') chip-medio
          @elseif($area['risk_level'] === 'Alto') chip-alto
          @else chip-muyalto
          @endif">
          {{ $area['risk_level'] }}
        </span>
      </div>
    </div>
    @endif
  @endforeach
  @endif

  @if(!empty($include['employee_list']) && $results->count())
  <!-- ══ TABLA DE EMPLEADOS ══ -->
  <div class="page-break"></div>
  <div class="section-title">Detalle de Evaluaciones</div>

  <table class="data-table">
    <thead>
      <tr>
        <th>Empleado</th>
        <th>Área</th>
        <th>Guía</th>
        <th>Puntaje</th>
        <th>Nivel de Riesgo</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      @foreach($results as $result)
      <tr>
        <td>
          <div class="emp-name">{{ $result->evaluation->employee->first_name }} {{ $result->evaluation->employee->last_name }}</div>
          <div class="emp-email">{{ $result->evaluation->employee->email }}</div>
        </td>
        <td>{{ $result->evaluation->employee->area->name ?? '—' }}</td>
        <td>{{ $result->evaluation->questionnaire->name ?? '—' }}</td>
        <td style="font-weight:bold; color:#0C4A6E;">{{ $result->score }}</td>
        <td>
          <span class="badge
            @if($result->risk_level === 'Bajo') chip-bajo
            @elseif($result->risk_level === 'Medio') chip-medio
            @elseif($result->risk_level === 'Alto') chip-alto
            @else chip-muyalto
            @endif">
            {{ $result->risk_level }}
          </span>
        </td>
        <td>{{ $result->created_at->format('d/m/Y') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif

  @if(!empty($include['recommendations']))
  <!-- ══ RECOMENDACIONES ══ -->
  <hr class="divider-thin"/>
  <div class="section-title">Recomendaciones NOM-035-STPS</div>

  @if($riskCounts['Bajo'] > 0)
  <div class="rec-box bajo no-break">
    <div class="rec-title" style="color:#16A34A;">✓ Riesgo Bajo ({{ $riskCounts['Bajo'] }} empleados)</div>
    <div class="rec-text">Los trabajadores presentan un nivel de riesgo bajo. Se recomienda mantener las condiciones actuales de trabajo y realizar evaluaciones de seguimiento de manera anual conforme lo establece la NOM-035-STPS-2018.</div>
  </div>
  @endif

  @if($riskCounts['Medio'] > 0)
  <div class="rec-box medio no-break">
    <div class="rec-title" style="color:#D97706;">⚠ Riesgo Medio ({{ $riskCounts['Medio'] }} empleados)</div>
    <div class="rec-text">Se detectaron factores de riesgo moderados. Se recomienda implementar medidas de intervención preventiva, revisar las condiciones y organización del trabajo, y establecer un plan de seguimiento trimestral. Documentar las acciones en el registro de seguimiento NOM-035.</div>
  </div>
  @endif

  @if($riskCounts['Alto'] > 0)
  <div class="rec-box alto no-break">
    <div class="rec-title" style="color:#DC2626;">✗ Riesgo Alto ({{ $riskCounts['Alto'] }} empleados)</div>
    <div class="rec-text">Nivel de riesgo alto detectado. Es obligatorio implementar un programa de intervención inmediato, proporcionar atención psicológica o médica según corresponda, modificar las condiciones de trabajo identificadas como factores de riesgo, y dar seguimiento mensual. Notificar a la STPS en caso de requerirlo.</div>
  </div>
  @endif

  @if($riskCounts['Muy Alto'] > 0)
  <div class="rec-box muyalto no-break">
    <div class="rec-title" style="color:#7C3AED;">✗ Riesgo Muy Alto ({{ $riskCounts['Muy Alto'] }} empleados)</div>
    <div class="rec-text">Riesgo psicosocial muy alto. Se requiere intervención inmediata: evaluación psicológica especializada, revisión urgente de condiciones laborales, posible reasignación de funciones, y notificación obligatoria ante la STPS conforme al artículo 8 de la NOM-035-STPS-2018. Documentar todas las acciones tomadas.</div>
  </div>
  @endif
  @endif

  <!-- ══ FIRMA ══ -->
  <div style="margin-top: 24px; display: table; width: 100%;">
    <div style="display: table-cell; width: 50%; text-align: center; padding: 0 20px;">
      <div style="border-top: 1px solid #CBD5E1; padding-top: 6px; margin-top: 40px;">
        <div style="font-size: 9px; font-weight: bold; color: #374151;">Responsable de Seguridad y Salud</div>
        <div style="font-size: 8px; color: #94A3B8; margin-top: 2px;">{{ $company->name }}</div>
      </div>
    </div>
    <div style="display: table-cell; width: 50%; text-align: center; padding: 0 20px;">
      <div style="border-top: 1px solid #CBD5E1; padding-top: 6px; margin-top: 40px;">
        <div style="font-size: 9px; font-weight: bold; color: #374151;">Representante de los Trabajadores</div>
        <div style="font-size: 8px; color: #94A3B8; margin-top: 2px;">Comisión de Seguridad e Higiene</div>
      </div>
    </div>
  </div>

</div><!-- /page -->
</body>
</html>