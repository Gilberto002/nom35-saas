<template>
  <MainLayout current-page="/dashboard" page-title="Dashboard">
    <div class="dashboard">

      <!-- Welcome banner -->
      <div class="welcome-banner">
        <div class="welcome-left">
          <span class="welcome-eyebrow">{{ todayFormatted }}</span>
          <h2 class="welcome-title">Bienvenido, <span class="welcome-name">{{ firstName }}</span></h2>
          <p class="welcome-sub">Resumen de cumplimiento NOM-035 de tu empresa</p>
        </div>
        <div class="welcome-right">
          <div class="compliance-wrap">
            <div class="compliance-big">{{ compliancePercent }}<span class="compliance-sym">%</span></div>
            <div class="compliance-meta">
              <span class="compliance-label">CUMPLIMIENTO</span>
              <div class="compliance-bar">
                <div class="compliance-fill" :style="{ width: compliancePercent + '%' }"></div>
              </div>
              <span class="compliance-detail">{{ stats.evaluated }} de {{ stats.total_employees }} empleados</span>
            </div>
          </div>
        </div>
      </div>

      <!-- KPI cards -->
      <div class="kpi-grid">
        <div v-for="(kpi, i) in kpiCards" :key="kpi.label" class="kpi-card" :class="kpi.mod" :style="{ '--i': i }">
          <div class="kpi-header">
            <div class="kpi-icon" v-html="kpi.icon"></div>
            <span class="kpi-badge" :class="kpi.trendUp ? 'badge-up' : 'badge-neutral'">{{ kpi.trend }}</span>
          </div>
          <div class="kpi-value">{{ kpi.value }}</div>
          <div class="kpi-label">{{ kpi.label }}</div>
          <div class="kpi-bar-track">
            <div class="kpi-bar-fill" :style="{ width: kpi.barPct + '%' }"></div>
          </div>
        </div>
      </div>

      <!-- Mid row -->
      <div class="mid-row">

        <!-- Risk breakdown -->
        <div class="panel">
          <div class="panel-header">
            <div>
              <p class="panel-eyebrow">DISTRIBUCIÓN</p>
              <h3 class="panel-title">Niveles de Riesgo</h3>
            </div>
          </div>
          <div class="risk-list">
            <div v-for="r in riskBreakdown" :key="r.label" class="risk-row">
              <div class="risk-left">
                <span class="risk-dot" :style="{ background: r.color }"></span>
                <span class="risk-name">{{ r.label }}</span>
              </div>
              <div class="risk-track">
                <div class="risk-fill" :style="{ width: r.pct + '%', background: r.color }"></div>
              </div>
              <div class="risk-right">
                <span class="risk-count">{{ r.count }}</span>
                <span class="risk-pct">{{ r.pct }}%</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Area ranking -->
        <div class="panel">
          <div class="panel-header">
            <div>
              <p class="panel-eyebrow">RANKING</p>
              <h3 class="panel-title">Por Área</h3>
            </div>
            <span class="panel-chip">{{ areaRanking.length }} áreas</span>
          </div>
          <div class="area-list">
            <div v-for="(area, i) in areaRanking" :key="area.name" class="area-row">
              <span class="area-idx" :class="{ 'area-idx--hot': i === 0 }">{{ String(i+1).padStart(2,'0') }}</span>
              <div class="area-info">
                <span class="area-name">{{ area.name }}</span>
                <span class="area-emp">{{ area.employees }} empleados</span>
              </div>
              <div class="area-score-wrap">
                <div class="area-bar">
                  <div class="area-bar-fill" :style="{ width: area.score + '%', background: riskColor(area.risk) }"></div>
                </div>
                <span class="area-score">{{ area.score }}</span>
                <span class="risk-chip" :class="riskClass(area.risk)">{{ area.risk }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Bottom row -->
      <div class="bottom-row">

        <!-- Recent evaluations -->
        <div class="panel">
          <div class="panel-header">
            <div>
              <p class="panel-eyebrow">ACTIVIDAD RECIENTE</p>
              <h3 class="panel-title">Evaluaciones</h3>
            </div>
            <Link href="/results" class="see-all">Ver todas →</Link>
          </div>
          <div class="eval-list">
            <div v-for="ev in recentEvaluations" :key="ev.id" class="eval-row">
              <div class="eval-avatar" :style="{ background: avatarGradient(ev.name) }">{{ initials(ev.name) }}</div>
              <div class="eval-info">
                <span class="eval-name">{{ ev.name }}</span>
                <span class="eval-meta">{{ ev.area }} · {{ ev.guide }}</span>
              </div>
              <div class="eval-right">
                <span class="eval-score">{{ ev.score }}</span>
                <span class="risk-chip" :class="riskClass(ev.risk)">{{ ev.risk }}</span>
                <span class="eval-date">{{ ev.date }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Pending -->
        <div class="panel panel--warn">
          <div class="panel-header">
            <div>
              <p class="panel-eyebrow" style="color:#E8180C">PENDIENTES</p>
              <h3 class="panel-title">Sin Evaluar</h3>
            </div>
            <span class="warn-badge">{{ pendingEmployees.length }}</span>
          </div>
          <div class="pending-list">
            <div v-for="emp in pendingEmployees.slice(0,5)" :key="emp.id" class="pending-row">
              <div class="pending-avatar" :style="{ background: avatarGradient(emp.name) }">{{ initials(emp.name) }}</div>
              <div class="pending-info">
                <span class="pending-name">{{ emp.name }}</span>
                <span class="pending-area">{{ emp.area }}</span>
              </div>
              <Link :href="`/evaluations/start?employee_id=${emp.id}`" class="eval-btn">
                Evaluar
                <svg viewBox="0 0 14 14" fill="none"><path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </Link>
            </div>
          </div>
          <div v-if="pendingEmployees.length > 5" class="pending-more">
            + {{ pendingEmployees.length - 5 }} más pendientes
          </div>
        </div>

      </div>

    </div>
  </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import MainLayout from '../Layouts/MainLayout.vue'
import axios from 'axios'

const page = usePage()
const userName = computed(() => page.props.auth?.user?.name || 'Usuario')
const firstName = computed(() => userName.value.split(' ')[0])

const todayFormatted = computed(() =>
  new Date().toLocaleDateString('es-MX', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
)

const stats = ref({ total_employees: 48, evaluated: 31, pending: 17, high_risk: 8, evaluations_this_month: 14, avg_score: 34 })
const riskData = ref({ bajo: 18, medio: 10, alto: 7, muyAlto: 1 })

const compliancePercent = computed(() => {
  const t = stats.value.total_employees
  return t ? Math.round((stats.value.evaluated / t) * 100) : 0
})

const kpiCards = computed(() => [
  {
    label: 'Total Empleados', value: stats.value.total_employees,
    mod: '', trend: '+3 este mes', trendUp: true, barPct: 100,
    icon: `<svg viewBox="0 0 24 24" fill="none"><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M16 3.13a4 4 0 010 7.75M21 21v-2a4 4 0 00-3-3.87" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>`,
  },
  {
    label: 'Evaluados', value: stats.value.evaluated,
    mod: 'kpi--dark', trend: `${compliancePercent.value}% del total`, trendUp: true,
    barPct: compliancePercent.value,
    icon: `<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/><path d="M8 12l3 3 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  },
  {
    label: 'Pendientes', value: stats.value.pending,
    mod: '', trend: 'Sin evaluación', trendUp: false,
    barPct: stats.value.total_employees ? Math.round(stats.value.pending / stats.value.total_employees * 100) : 0,
    icon: `<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>`,
  },
  {
    label: 'Riesgo Alto / Muy Alto', value: stats.value.high_risk,
    mod: 'kpi--red', trend: 'Requieren atención', trendUp: false,
    barPct: stats.value.total_employees ? Math.round(stats.value.high_risk / stats.value.total_employees * 100) : 0,
    icon: `<svg viewBox="0 0 24 24" fill="none"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M12 9v4M12 17h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>`,
  },
])

const totalRisk = computed(() => Object.values(riskData.value).reduce((a, b) => a + b, 0))
function pct(n) { return totalRisk.value ? Math.round(n / totalRisk.value * 100) : 0 }
const riskBreakdown = computed(() => [
  { label: 'Bajo',     color: '#22C55E', count: riskData.value.bajo,    pct: pct(riskData.value.bajo) },
  { label: 'Medio',    color: '#F59E0B', count: riskData.value.medio,   pct: pct(riskData.value.medio) },
  { label: 'Alto',     color: '#E8180C', count: riskData.value.alto,    pct: pct(riskData.value.alto) },
  { label: 'Muy Alto', color: '#0A0A0A', count: riskData.value.muyAlto, pct: pct(riskData.value.muyAlto) },
])

const areaRanking = ref([
  { name: 'Operaciones',      score: 58, risk: 'Alto',  employees: 14 },
  { name: 'TI',               score: 42, risk: 'Medio', employees: 8  },
  { name: 'Ventas',           score: 36, risk: 'Medio', employees: 11 },
  { name: 'Finanzas',         score: 22, risk: 'Bajo',  employees: 7  },
  { name: 'Recursos Humanos', score: 14, risk: 'Bajo',  employees: 8  },
])

const recentEvaluations = ref([
  { id:1, name:'Ana Torres',    area:'RRHH',        guide:'Guía II', score:22, risk:'Medio',    date:'Hoy'  },
  { id:2, name:'Carlos Vega',   area:'Operaciones', guide:'Guía II', score:58, risk:'Alto',     date:'Ayer' },
  { id:3, name:'Sofía Ruiz',    area:'Ventas',      guide:'Guía II', score:14, risk:'Bajo',     date:'Ayer' },
  { id:4, name:'Miguel Olvera', area:'TI',          guide:'Guía II', score:72, risk:'Muy Alto', date:'Lun'  },
  { id:5, name:'Laura Méndez',  area:'Operaciones', guide:'Guía II', score:35, risk:'Medio',    date:'Lun'  },
])

const pendingEmployees = ref([
  { id:6,  name:'José Ramírez',  area:'Ventas'      },
  { id:7,  name:'Patricia Leal', area:'RRHH'        },
  { id:8,  name:'Roberto Cruz',  area:'TI'          },
  { id:9,  name:'Diana Flores',  area:'Operaciones' },
  { id:10, name:'Luis Morales',  area:'Finanzas'    },
  { id:11, name:'Carmen Ibarra', area:'Ventas'      },
])

function riskColor(level) {
  return { Bajo:'#22C55E', Medio:'#F59E0B', Alto:'#E8180C', 'Muy Alto':'#0A0A0A' }[level] || '#94A3B8'
}
function riskClass(level) {
  return { Bajo:'chip-bajo', Medio:'chip-medio', Alto:'chip-alto', 'Muy Alto':'chip-muyalto' }[level] || ''
}
function initials(name) {
  return name?.split(' ').map(n => n[0]).join('').slice(0,2).toUpperCase() || '??'
}
const gradients = [
  'linear-gradient(135deg,#0A0A0A,#E8180C)',
  'linear-gradient(135deg,#E8180C,#F59E0B)',
  'linear-gradient(135deg,#374151,#6B7280)',
  'linear-gradient(135deg,#0A0A0A,#374151)',
  'linear-gradient(135deg,#E8180C,#0A0A0A)',
]
function avatarGradient(name) {
  return gradients[(name?.charCodeAt(0) || 0) % gradients.length]
}

async function loadDashboard() {
  try {
    const { data } = await axios.get('/api/dashboard')
    if (data.stats)   stats.value            = data.stats
    if (data.risk)    riskData.value          = data.risk
    if (data.areas)   areaRanking.value       = data.areas
    if (data.recent)  recentEvaluations.value = data.recent
    if (data.pending) pendingEmployees.value  = data.pending
  } catch (e) { console.log('Usando datos demo') }
}
onMounted(() => loadDashboard())
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600;700&display=swap');

:root {
  --black:  #0A0A0A;
  --red:    #E8180C;
  --bg:     #F2F2F0;
  --white:  #ffffff;
  --muted:  #7A7A72;
  --border: #E4E4E0;
  --radius: 12px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.dashboard {
  display: flex; flex-direction: column; gap: 20px;
  font-family: 'DM Sans', sans-serif;
  color: var(--black);
}

/* ══ Welcome banner ══ */
.welcome-banner {
  background: var(--black);
  border-radius: var(--radius);
  padding: 32px 36px;
  display: flex; align-items: center; justify-content: space-between;
  gap: 32px; overflow: hidden; position: relative;
}
.welcome-banner::before {
  content: '';
  position: absolute; inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
  background-size: 32px 32px;
  pointer-events: none;
}
.welcome-banner::after {
  content: '';
  position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
  background: var(--red);
  border-radius: var(--radius) 0 0 var(--radius);
}
.welcome-left { position: relative; z-index: 1; }
.welcome-eyebrow {
  display: block; font-size: 10px; font-weight: 700;
  letter-spacing: 2px; text-transform: uppercase;
  color: var(--red); margin-bottom: 8px;
}
.welcome-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 42px; letter-spacing: 1.5px; line-height: 1;
  color: var(--white); margin-bottom: 8px;
}
.welcome-name { color: var(--red); }
.welcome-sub { font-size: 13px; color: rgba(255,255,255,0.4); }

.welcome-right { position: relative; z-index: 1; flex-shrink: 0; }
.compliance-wrap { display: flex; align-items: center; gap: 20px; }
.compliance-big {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 80px; line-height: 1; color: var(--white); letter-spacing: -1px;
}
.compliance-sym { font-size: 42px; color: var(--red); }
.compliance-meta { display: flex; flex-direction: column; gap: 6px; }
.compliance-label {
  font-size: 9px; font-weight: 700; letter-spacing: 2.5px; color: rgba(255,255,255,0.3);
}
.compliance-bar { width: 140px; height: 3px; background: rgba(255,255,255,0.1); }
.compliance-fill { height: 100%; background: var(--red); transition: width 1.2s ease; }
.compliance-detail { font-size: 11px; color: rgba(255,255,255,0.3); }

/* ══ KPI grid ══ */
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }

/* KPI card entrance animation */
@keyframes kpi-in {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}

.kpi-card {
  background: var(--white);
  border-radius: var(--radius);
  border: 1.5px solid var(--border);
  padding: 22px 20px;
  position: relative; overflow: hidden;
  animation: kpi-in 0.45s cubic-bezier(0.22,1,0.36,1) both;
  animation-delay: calc(var(--i, 0) * 80ms);
  transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
  cursor: default;
}
.kpi-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 28px rgba(0,0,0,0.09);
  border-color: #C8C8C4;
}
/* shimmer sweep on hover */
.kpi-card::after {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.45) 50%, transparent 60%);
  transform: translateX(-100%);
  transition: transform 0.55s ease;
  pointer-events: none;
}
.kpi-card:hover::after { transform: translateX(100%); }

.kpi-card::before {
  content: ''; position: absolute;
  top: 0; left: 0; right: 0; height: 2.5px;
  border-radius: var(--radius) var(--radius) 0 0;
}
.kpi-card:nth-child(1)::before { background: var(--black); }
.kpi-card:nth-child(2)::before { background: var(--red); }
.kpi-card:nth-child(3)::before { background: #F59E0B; }
.kpi-card:nth-child(4)::before { background: var(--red); }

/* ── todas las cards son blancas ── */
.kpi-card.kpi--dark,
.kpi-card.kpi--red {
  background: var(--white);
  border-color: var(--border);
}
/* Riesgo Alto: solo el número en rojo + borde top rojo ── */
.kpi-card.kpi--red .kpi-value { color: var(--red); }
.kpi-card.kpi--red .kpi-bar-fill { background: var(--red); }
.kpi-card.kpi--red .badge-neutral { background: #FEE2E2; color: var(--red); }

.kpi-header {
  display: flex; justify-content: space-between; align-items: flex-start;
  margin-bottom: 14px;
}
.kpi-icon { width: 20px; height: 20px; color: var(--black); opacity: 0.22; }
.kpi-icon :deep(svg) { width: 20px; height: 20px; }
.kpi-badge {
  font-size: 10px; font-weight: 600;
  padding: 3px 9px; border-radius: 100px; white-space: nowrap;
}
.badge-up      { background: #F0FDF4; color: #16A34A; }
.badge-neutral { background: #FFF7ED; color: #D97706; }

.kpi-value {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 48px; line-height: 0.9;
  color: var(--black); margin-bottom: 4px;
}
.kpi-label { font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 16px; }
.kpi-bar-track { height: 3px; background: var(--border); border-radius: 100px; overflow: hidden; }

@keyframes bar-grow {
  from { width: 0 !important; }
}
.kpi-bar-fill {
  height: 100%; background: var(--black); border-radius: 100px;
  animation: bar-grow 1s cubic-bezier(0.22,1,0.36,1) both;
  animation-delay: calc(var(--i, 0) * 80ms + 300ms);
}

/* ══ Panels ══ */
.panel {
  background: var(--white); border-radius: var(--radius);
  border: 1.5px solid var(--border); padding: 24px;
}
.panel--warn { border-color: #FECACA; }

.panel-header {
  display: flex; justify-content: space-between; align-items: flex-start;
  margin-bottom: 20px; padding-bottom: 14px;
  border-bottom: 1px solid var(--border);
}
.panel-eyebrow {
  font-size: 9px; font-weight: 700; letter-spacing: 2.5px;
  text-transform: uppercase; color: var(--muted); margin-bottom: 3px;
}
.panel-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 20px; letter-spacing: 1px; color: var(--black);
}
.panel-chip {
  font-size: 10px; font-weight: 700; padding: 4px 10px;
  border-radius: 100px; background: var(--bg); color: var(--muted);
  border: 1px solid var(--border);
}
.warn-badge {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 28px; line-height: 1; color: var(--red);
}

/* ══ Risk list ══ */
.risk-list { display: flex; flex-direction: column; gap: 16px; }
.risk-row  { display: flex; align-items: center; gap: 10px; }
.risk-left { display: flex; align-items: center; gap: 8px; width: 80px; flex-shrink: 0; }
.risk-dot  { width: 9px; height: 9px; border-radius: 2px; flex-shrink: 0; }
.risk-name { font-size: 12px; font-weight: 600; color: var(--black); }
.risk-track { flex: 1; height: 5px; background: var(--bg); border-radius: 100px; overflow: hidden; }
.risk-fill  { height: 100%; border-radius: 100px; transition: width 1s ease; }
.risk-right { display: flex; align-items: center; gap: 8px; }
.risk-count { font-family: 'Bebas Neue', sans-serif; font-size: 20px; color: var(--black); line-height: 1; width: 24px; text-align: right; }
.risk-pct   { font-size: 11px; color: var(--muted); width: 30px; text-align: right; }

/* ══ Area ranking ══ */
.area-list { display: flex; flex-direction: column; gap: 13px; }
.area-row  { display: flex; align-items: center; gap: 10px; }
.area-idx  { font-family: 'Bebas Neue', sans-serif; font-size: 14px; letter-spacing: 1px; color: #D4D4D0; width: 26px; flex-shrink: 0; }
.area-idx--hot { color: var(--red); }
.area-info { width: 120px; flex-shrink: 0; }
.area-name { display: block; font-size: 13px; font-weight: 600; color: var(--black); }
.area-emp  { display: block; font-size: 11px; color: var(--muted); }
.area-score-wrap { flex: 1; display: flex; align-items: center; gap: 8px; }
.area-bar  { flex: 1; height: 4px; background: var(--bg); border-radius: 100px; overflow: hidden; }
.area-bar-fill { height: 100%; border-radius: 100px; transition: width 1s ease; }
.area-score { font-family: 'Bebas Neue', sans-serif; font-size: 20px; color: var(--black); width: 26px; text-align: right; }

/* ══ Rows ══ */
.mid-row    { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.bottom-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

/* ══ Risk chips ══ */
.risk-chip { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 100px; white-space: nowrap; }
.chip-bajo    { background: #DCFCE7; color: #15803D; }
.chip-medio   { background: #FEF9C3; color: #B45309; }
.chip-alto    { background: #FEE2E2; color: var(--red); }
.chip-muyalto { background: var(--black); color: #fff; }

/* ══ Recent evals ══ */
.see-all {
  font-size: 12px; font-weight: 700; color: var(--red);
  text-decoration: none; letter-spacing: 0.3px;
  transition: opacity 0.2s; align-self: center;
}
.see-all:hover { opacity: 0.65; }

.eval-list { display: flex; flex-direction: column; }
.eval-row  { display: flex; align-items: center; gap: 12px; padding: 11px 0; border-bottom: 1px solid var(--bg); }
.eval-row:last-child { border-bottom: none; }
.eval-avatar {
  width: 36px; height: 36px; border-radius: 8px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 700; color: #fff;
}
.eval-info { flex: 1; min-width: 0; }
.eval-name { display: block; font-size: 13px; font-weight: 600; color: var(--black); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.eval-meta { display: block; font-size: 11px; color: var(--muted); }
.eval-right { display: flex; flex-direction: column; align-items: flex-end; gap: 3px; }
.eval-score { font-family: 'Bebas Neue', sans-serif; font-size: 22px; color: var(--black); line-height: 1; }
.eval-date  { font-size: 10px; color: #C4C4BC; }

/* ══ Pending ══ */
.pending-list { display: flex; flex-direction: column; }
.pending-row  { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--bg); }
.pending-row:last-child { border-bottom: none; }
.pending-avatar {
  width: 34px; height: 34px; border-radius: 8px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 700; color: #fff;
}
.pending-info { flex: 1; min-width: 0; }
.pending-name { display: block; font-size: 13px; font-weight: 600; color: var(--black); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.pending-area { display: block; font-size: 11px; color: var(--muted); }

.eval-btn {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 6px 13px; border-radius: 7px;
  background: var(--black); color: #fff;
  font-size: 12px; font-weight: 700;
  text-decoration: none; white-space: nowrap; flex-shrink: 0;
  transition: background 0.18s;
}
.eval-btn:hover { background: var(--red); }
.eval-btn svg { width: 11px; height: 11px; }
.pending-more { text-align: center; font-size: 11px; font-weight: 600; color: var(--muted); padding-top: 12px; }

/* ══ Responsive ══ */
@media (max-width: 1100px) {
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }
  .mid-row, .bottom-row { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
  .kpi-grid { grid-template-columns: 1fr; }
  .welcome-banner { flex-direction: column; gap: 24px; }
  .compliance-wrap { flex-direction: column; align-items: flex-start; gap: 12px; }
}
</style>