<template>
  <MainLayout current-page="/reports" page-title="Reportes PDF">
    <div class="reports-page">

      <!-- Banner -->
      <div class="reports-banner">
        <div class="banner-left">
          <div class="banner-icon">
            <svg viewBox="0 0 32 32" fill="none">
              <path d="M9 4h9l7 7v17a2 2 0 01-2 2H9a2 2 0 01-2-2V6a2 2 0 012-2z" stroke="white" stroke-width="1.5" stroke-linejoin="round"/>
              <path d="M18 4v7h7" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
              <path d="M12 17h8M12 21h6" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
          </div>
          <div>
            <p class="banner-eyebrow">CUMPLIMIENTO NOM-035 · STPS</p>
            <h2 class="banner-title">Reportes Oficiales</h2>
            <p class="banner-sub">Genera documentación de cumplimiento lista para auditorías y seguimiento organizacional.</p>
          </div>
        </div>
        <div class="banner-badges">
          <span class="b-badge">
            <svg viewBox="0 0 14 14" fill="none"><path d="M2 7l4 4 6-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Formato oficial
          </span>
          <span class="b-badge">
            <svg viewBox="0 0 14 14" fill="none"><path d="M2 7l4 4 6-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Listo para auditoría
          </span>
          <span class="b-badge">
            <svg viewBox="0 0 14 14" fill="none"><path d="M2 7l4 4 6-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Descarga inmediata
          </span>
        </div>
      </div>

      <!-- Report type selector -->
      <div class="section-block">
        <div class="section-head">
          <p class="section-eyebrow">PASO 1</p>
          <h3 class="section-title">Selecciona el tipo de reporte</h3>
        </div>
        <div class="report-types-grid">

          <div class="rtype-card" :class="{ selected: selectedType === 'company' }" @click="selectedType = 'company'">
            <div class="rtype-top">
              <div class="rtype-icon icon-dark">
                <svg viewBox="0 0 20 20" fill="none"><path d="M3 18V8l7-5 7 5v10H3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M8 18v-6h4v6" stroke="currentColor" stroke-width="1.4"/></svg>
              </div>
              <div class="rtype-check" v-if="selectedType === 'company'">
                <svg viewBox="0 0 12 12" fill="none"><path d="M2 6l3 3 5-5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
            </div>
            <h3 class="rtype-title">Organizacional</h3>
            <p class="rtype-desc">Resultados completos con distribución de riesgo y cumplimiento NOM-035 para toda la empresa.</p>
          </div>

          <div class="rtype-card" :class="{ selected: selectedType === 'area' }" @click="selectedType = 'area'">
            <div class="rtype-top">
              <div class="rtype-icon icon-neutral">
                <svg viewBox="0 0 20 20" fill="none"><rect x="2" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="11" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="2" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="11" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/></svg>
              </div>
              <div class="rtype-check" v-if="selectedType === 'area'">
                <svg viewBox="0 0 12 12" fill="none"><path d="M2 6l3 3 5-5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
            </div>
            <h3 class="rtype-title">Por Área</h3>
            <p class="rtype-desc">Análisis detallado de un área específica con resultados individuales y promedio de riesgo.</p>
          </div>

          <div class="rtype-card" :class="{ selected: selectedType === 'risk' }" @click="selectedType = 'risk'">
            <div class="rtype-top">
              <div class="rtype-icon icon-red">
                <svg viewBox="0 0 20 20" fill="none"><path d="M10 2l8 14H2L10 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M10 8v4M10 14h.01" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              </div>
              <div class="rtype-check" v-if="selectedType === 'risk'">
                <svg viewBox="0 0 12 12" fill="none"><path d="M2 6l3 3 5-5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
            </div>
            <h3 class="rtype-title">Riesgo Alto</h3>
            <p class="rtype-desc">Empleados con riesgo Alto o Muy Alto. Priorizado para atención inmediata ante la STPS.</p>
          </div>

          <div class="rtype-card" :class="{ selected: selectedType === 'individual' }" @click="selectedType = 'individual'">
            <div class="rtype-top">
              <div class="rtype-icon icon-neutral">
                <svg viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.4"/><path d="M3 18c0-3.9 3.1-7 7-7s7 3.1 7 7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              </div>
              <div class="rtype-check" v-if="selectedType === 'individual'">
                <svg viewBox="0 0 12 12" fill="none"><path d="M2 6l3 3 5-5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
            </div>
            <h3 class="rtype-title">Individual</h3>
            <p class="rtype-desc">Evaluación completa de un empleado específico con respuestas y recomendación personalizada.</p>
          </div>

        </div>
      </div>

      <!-- Config + Preview -->
      <div class="config-panel">

        <!-- Left: config -->
        <div class="config-left">
          <div class="section-head">
            <p class="section-eyebrow">PASO 2</p>
            <h3 class="section-title">Configurar parámetros</h3>
          </div>
          <div class="config-fields">
            <div class="config-field full">
              <label class="config-label">Período</label>
              <div class="date-row">
                <div class="date-wrap">
                  <svg viewBox="0 0 16 16" fill="none" class="date-icon"><rect x="2" y="3" width="12" height="11" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 1v4M11 1v4M2 7h12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                  <input v-model="config.dateFrom" type="date" class="date-input" />
                </div>
                <span class="date-sep">—</span>
                <div class="date-wrap">
                  <svg viewBox="0 0 16 16" fill="none" class="date-icon"><rect x="2" y="3" width="12" height="11" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 1v4M11 1v4M2 7h12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                  <input v-model="config.dateTo" type="date" class="date-input" />
                </div>
              </div>
            </div>

            <div class="config-field">
              <label class="config-label">Área</label>
              <select v-model="config.area_id" class="config-select">
                <option value="">Todas las áreas</option>
                <option v-for="a in areas" :key="a.id" :value="a.id">{{ a.name }}</option>
              </select>
            </div>

            <div class="config-field">
              <label class="config-label">Guía NOM-035</label>
              <select v-model="config.questionnaire_id" class="config-select">
                <option value="">Todas las guías</option>
                <option value="1">Guía I</option>
                <option value="2">Guía II</option>
                <option value="3">Guía III</option>
              </select>
            </div>

            <div class="config-field">
              <label class="config-label">Nivel de riesgo</label>
              <select v-model="config.risk_level" class="config-select">
                <option value="">Todos los niveles</option>
                <option value="Bajo">Bajo</option>
                <option value="Medio">Medio</option>
                <option value="Alto">Alto</option>
                <option value="Muy Alto">Muy Alto</option>
              </select>
            </div>

            <div class="config-field full">
              <label class="config-label">Incluir en el reporte</label>
              <div class="include-grid">
                <label v-for="opt in includeOptions" :key="opt.key" class="include-item">
                  <input type="checkbox" v-model="config.include[opt.key]" class="inc-check"/>
                  <span class="inc-custom"></span>
                  <span class="inc-label">{{ opt.label }}</span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: preview + generate -->
        <div class="config-right">
          <div class="section-head">
            <p class="section-eyebrow">PASO 3</p>
            <h3 class="section-title">Vista previa y descarga</h3>
          </div>

          <div class="preview-box">
            <div class="preview-page">
              <div class="mock-header">
                <div class="mock-logo"></div>
                <div class="mock-title-area">
                  <div class="mock-line w-60 dark"></div>
                  <div class="mock-line w-40"></div>
                </div>
                <div class="mock-badge-block"></div>
              </div>
              <div class="mock-divider"></div>
              <div class="mock-info-row">
                <div v-for="i in 3" :key="i" class="mock-info-col">
                  <div class="mock-line w-50 sm"></div>
                  <div class="mock-line w-30 dark sm"></div>
                </div>
              </div>
              <div class="mock-chart">
                <div class="mock-donut"></div>
                <div class="mock-bars">
                  <div v-for="w in [80,60,40,25]" :key="w" class="mock-bar" :style="{ width: w+'%' }"></div>
                </div>
              </div>
              <div class="mock-table">
                <div class="mock-thead">
                  <div v-for="i in 5" :key="i" class="mock-th"></div>
                </div>
                <div v-for="r in 4" :key="r" class="mock-trow">
                  <div v-for="i in 5" :key="i" class="mock-td"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="report-meta">
            <div class="rm-row">
              <span class="rm-label">Tipo</span>
              <span class="rm-value">{{ currentReportType?.title }}</span>
            </div>
            <div class="rm-row">
              <span class="rm-label">Período</span>
              <span class="rm-value">{{ config.dateFrom || '—' }} / {{ config.dateTo || '—' }}</span>
            </div>
            <div class="rm-row">
              <span class="rm-label">Formato</span>
              <span class="rm-value">PDF · A4</span>
            </div>
          </div>

          <button class="btn-generate" @click="generateReport" :disabled="isGenerating">
            <svg v-if="!isGenerating" viewBox="0 0 20 20" fill="none">
              <path d="M10 2v12M5 10l5 6 5-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M3 17h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span v-if="!isGenerating">Generar y descargar PDF</span>
            <span v-else class="gen-loading">
              <span class="spin-ring"></span>
              Generando PDF...
            </span>
          </button>
        </div>
      </div>

      <!-- Recent reports -->
      <div class="section-block">
        <div class="section-head">
          <div>
            <p class="section-eyebrow">HISTORIAL</p>
            <h3 class="section-title">Reportes generados</h3>
          </div>
        </div>
        <div class="recent-table">
          <div class="rt-header">
            <span>Nombre del reporte</span>
            <span>Tipo</span>
            <span>Período</span>
            <span>Generado</span>
            <span>Acciones</span>
          </div>
          <div v-for="rep in recentReports" :key="rep.id" class="rt-row">
            <div class="rt-name">
              <div class="rt-icon">
                <svg viewBox="0 0 16 16" fill="none"><path d="M4 1h6l4 4v9a1 1 0 01-1 1H4a1 1 0 01-1-1V2a1 1 0 011-1z" stroke="#E8180C" stroke-width="1.3"/><path d="M9 1v4h4" stroke="#E8180C" stroke-width="1.3" stroke-linecap="round"/></svg>
              </div>
              <div>
                <p class="rt-title">{{ rep.name }}</p>
                <p class="rt-size">{{ rep.size }}</p>
              </div>
            </div>
            <span class="rt-type">{{ rep.type }}</span>
            <span class="rt-period">{{ rep.period }}</span>
            <span class="rt-date">{{ rep.date }}</span>
            <div class="rt-actions">
              <button class="rt-btn download" @click="downloadReport(rep)" title="Descargar">
                <svg viewBox="0 0 16 16" fill="none"><path d="M8 2v9M4 8l4 5 4-5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 14h12" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              </button>
              <button class="rt-btn delete" @click="deleteReport(rep.id)" title="Eliminar">
                <svg viewBox="0 0 16 16" fill="none"><path d="M3 4h10M6 4V3h4v1M5 4v8a1 1 0 001 1h4a1 1 0 001-1V4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
              </button>
            </div>
          </div>
          <div v-if="recentReports.length === 0" class="rt-empty">
            No hay reportes generados aún
          </div>
        </div>
      </div>

    </div>

    <!-- Toast -->
    <Teleport to="body">
      <Transition name="toast">
        <div v-if="toast.show" class="toast" :class="toast.type">
          <svg v-if="toast.type === 'success'" viewBox="0 0 20 20" fill="none">
            <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/>
            <path d="M6.5 10l2.5 2.5 4-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <svg v-else viewBox="0 0 20 20" fill="none">
            <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/>
            <path d="M10 6v5M10 13.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
          {{ toast.message }}
        </div>
      </Transition>
    </Teleport>
  </MainLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import MainLayout from '../Layouts/MainLayout.vue'
import axios from 'axios'

const isGenerating = ref(false)
const selectedType = ref('company')
const areas = ref([])

const config = reactive({
  dateFrom: new Date(new Date().getFullYear(), 0, 1).toISOString().split('T')[0],
  dateTo:   new Date().toISOString().split('T')[0],
  area_id: '',
  questionnaire_id: '',
  risk_level: '',
  include: {
    summary:         true,
    risk_chart:      true,
    area_table:      true,
    employee_list:   true,
    recommendations: true,
  },
})

const includeOptions = [
  { key: 'summary',          label: 'Resumen ejecutivo' },
  { key: 'risk_chart',       label: 'Gráfica de riesgo' },
  { key: 'area_table',       label: 'Tabla por áreas' },
  { key: 'employee_list',    label: 'Lista de empleados' },
  { key: 'recommendations',  label: 'Recomendaciones STPS' },
]

const reportTypes = [
  {
    id: 'company',
    title: 'Organizacional',
    desc: 'Resultados completos con distribución de riesgo y cumplimiento NOM-035 para toda la empresa.',
    color: 'icon-dark',
    icon: `<svg viewBox="0 0 20 20" fill="none"><path d="M3 18V8l7-5 7 5v10H3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M8 18v-6h4v6" stroke="currentColor" stroke-width="1.4"/></svg>`,
  },
  {
    id: 'area',
    title: 'Por Área',
    desc: 'Análisis detallado de un área específica con resultados individuales y promedio de riesgo.',
    color: 'icon-neutral',
    icon: `<svg viewBox="0 0 20 20" fill="none"><rect x="2" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="11" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="2" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="11" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/></svg>`,
  },
  {
    id: 'risk',
    title: 'Riesgo Alto',
    desc: 'Empleados con riesgo Alto o Muy Alto. Priorizado para atención inmediata ante la STPS.',
    color: 'icon-red',
    icon: `<svg viewBox="0 0 20 20" fill="none"><path d="M10 2l8 14H2L10 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M10 8v4M10 14h.01" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>`,
  },
  {
    id: 'individual',
    title: 'Individual',
    desc: 'Evaluación completa de un empleado específico con respuestas y recomendación personalizada.',
    color: 'icon-neutral',
    icon: `<svg viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.4"/><path d="M3 18c0-3.9 3.1-7 7-7s7 3.1 7 7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>`,
  },
]

const currentReportType = computed(() => reportTypes.find(r => r.id === selectedType.value))

const recentReports = ref([
  { id:1, name:'Reporte Organizacional Mayo 2025', type:'Organizacional', period:'Ene – May 2025', date:'Hace 2 días', size:'842 KB', url:'/reports/1' },
  { id:2, name:'Riesgo Alto – Operaciones',        type:'Área',          period:'Abr – May 2025', date:'Hace 5 días', size:'310 KB', url:'/reports/2' },
  { id:3, name:'Evaluación Carlos Vega',           type:'Individual',    period:'May 2025',       date:'Hace 1 sem',  size:'124 KB', url:'/reports/3' },
])

const toast = reactive({ show: false, message: '', type: 'success' })

async function fetchAreas() {
  try {
    const { data } = await axios.get('/api/areas')
    areas.value = data.data || data
  } catch { /* silent */ }
}

async function generateReport() {
  isGenerating.value = true
  try {
    const params = new URLSearchParams({
      type: selectedType.value,
      date_from: config.dateFrom,
      date_to:   config.dateTo,
      ...(config.area_id          && { area_id: config.area_id }),
      ...(config.questionnaire_id && { questionnaire_id: config.questionnaire_id }),
      ...(config.risk_level       && { risk_level: config.risk_level }),
      include: JSON.stringify(config.include),
    })

    const response = await axios.get(`/api/reports/generate?${params}`, { responseType: 'blob' })
    const url  = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }))
    const link = document.createElement('a')
    link.href  = url
    link.setAttribute('download', `NOM035_${selectedType.value}_${config.dateFrom}_${config.dateTo}.pdf`)
    document.body.appendChild(link)
    link.click()
    link.remove()
    window.URL.revokeObjectURL(url)

    showToast('Reporte generado y descargado correctamente')

    recentReports.value.unshift({
      id: Date.now(),
      name: `${currentReportType.value.title} ${new Date().toLocaleDateString('es-MX')}`,
      type: currentReportType.value.title,
      period: `${config.dateFrom} – ${config.dateTo}`,
      date: 'Ahora',
      size: '—',
      url: '#',
    })
  } catch (e) {
    showToast('Error al generar el reporte. Verifica la configuración.', 'error')
  } finally {
    isGenerating.value = false
  }
}

function downloadReport(rep) { window.open(rep.url, '_blank') }
function deleteReport(id) {
  recentReports.value = recentReports.value.filter(r => r.id !== id)
  showToast('Reporte eliminado del historial')
}
function showToast(message, type = 'success') {
  toast.message = message; toast.type = type; toast.show = true
  setTimeout(() => toast.show = false, 3500)
}

onMounted(() => fetchAreas())
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600;700&display=swap');
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --black:  #0A0A0A;
  --red:    #E8180C;
  --bg:     #F2F2F0;
  --white:  #ffffff;
  --muted:  #7A7A72;
  --border: #E4E4E0;
  --radius: 12px;
}

.reports-page { display: flex; flex-direction: column; gap: 20px; font-family: 'DM Sans', sans-serif; }

/* ══ Banner ══ */
.reports-banner {
  background: var(--black);
  border-radius: var(--radius);
  padding: 28px 32px;
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;
  position: relative; overflow: hidden;
  background-image:
    linear-gradient(rgba(255,255,255,0.022) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.022) 1px, transparent 1px);
  background-size: 28px 28px;
}
.reports-banner::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: var(--red); pointer-events: none;
}
.banner-left { display: flex; align-items: center; gap: 18px; }
.banner-icon {
  width: 48px; height: 48px; flex-shrink: 0;
  background: rgba(255,255,255,0.07);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
}
.banner-icon svg { width: 26px; height: 26px; }
.banner-eyebrow {
  font-size: 9px; font-weight: 700; letter-spacing: 2.5px;
  color: var(--red); margin-bottom: 4px;
}
.banner-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 32px; letter-spacing: 1.5px; line-height: 1; color: var(--white);
  margin-bottom: 6px;
}
.banner-sub { font-size: 13px; color: rgba(255,255,255,0.4); max-width: 420px; }
.banner-badges { display: flex; gap: 8px; flex-wrap: wrap; }
.b-badge {
  display: inline-flex; align-items: center; gap: 5px;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.1);
  color: rgba(255,255,255,0.6);
  font-size: 11px; font-weight: 600;
  padding: 5px 12px; border-radius: 100px; white-space: nowrap;
}
.b-badge svg { width: 11px; height: 11px; color: #4ADE80; }

/* ══ Section blocks ══ */
.section-block {
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  padding: 24px;
}
.section-head { margin-bottom: 18px; }
.section-eyebrow {
  font-size: 9px; font-weight: 700; letter-spacing: 2.5px;
  color: var(--red); margin-bottom: 3px;
}
.section-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 20px; letter-spacing: 1px; color: var(--black);
}

/* ══ Report types ══ */
.report-types-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }

.rtype-card {
  background: var(--bg);
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  padding: 18px;
  cursor: pointer;
  transition: all 0.18s;
  position: relative;
}
.rtype-card:hover { border-color: #C4C4C0; background: var(--white); }
.rtype-card.selected {
  background: var(--white);
  border-color: var(--black);
  box-shadow: 0 0 0 3px rgba(10,10,10,0.06);
}
.rtype-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
.rtype-icon {
  width: 38px; height: 38px; border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.rtype-icon svg { width: 18px; height: 18px; display: block; }
.icon-dark    { background: var(--black); color: var(--white); }
.icon-red     { background: #FEE2E2; color: var(--red); }
.icon-neutral { background: var(--bg); border: 1.5px solid var(--border); color: var(--black); }

.rtype-check {
  width: 20px; height: 20px;
  background: var(--black); border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
}
.rtype-check svg { width: 12px; height: 12px; }

.rtype-title {
  font-size: 13px; font-weight: 700; color: var(--black);
  margin-bottom: 6px; letter-spacing: 0.2px;
}
.rtype-desc { font-size: 11px; color: var(--muted); line-height: 1.55; }

/* ══ Config panel ══ */
.config-panel {
  display: grid; grid-template-columns: 1fr 340px; gap: 0;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
}
.config-left { padding: 24px; }
.config-right {
  padding: 24px;
  border-left: 1.5px solid var(--border);
  background: var(--bg);
}

.config-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.config-field { display: flex; flex-direction: column; gap: 6px; }
.config-field.full { grid-column: span 2; }
.config-label {
  font-size: 10px; font-weight: 700; letter-spacing: 1px;
  text-transform: uppercase; color: var(--black);
}

.date-row { display: flex; align-items: center; gap: 8px; }
.date-sep { color: var(--muted); font-size: 14px; }
.date-wrap { position: relative; flex: 1; }
.date-icon {
  position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
  width: 13px; height: 13px; color: var(--muted); pointer-events: none;
}
.date-input {
  width: 100%; padding: 9px 10px 9px 28px;
  border: 1.5px solid var(--border); border-radius: 8px;
  font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--black);
  background: var(--white); outline: none; transition: border-color 0.2s;
}
.date-input:focus { border-color: var(--black); }

.config-select {
  padding: 9px 30px 9px 11px;
  border: 1.5px solid var(--border); border-radius: 8px;
  font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--black);
  background: var(--white) url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M4 6l4 4 4-4' stroke='%237A7A72' stroke-width='1.4' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 8px center;
  appearance: none; outline: none; cursor: pointer; transition: border-color 0.2s;
}
.config-select:focus { border-color: var(--black); }

.include-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 9px; }
.include-item { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.inc-check { display: none; }
.inc-custom {
  width: 15px; height: 15px;
  border: 1.5px solid var(--border); border-radius: 3px; background: var(--white);
  transition: all 0.15s; position: relative; flex-shrink: 0;
}
.inc-check:checked + .inc-custom { background: var(--black); border-color: var(--black); }
.inc-check:checked + .inc-custom::after {
  content: ''; position: absolute; left: 3px; top: 1px;
  width: 5px; height: 8px; border: 2px solid white;
  border-top: none; border-left: none; transform: rotate(45deg);
}
.inc-label { font-size: 12px; font-weight: 500; color: var(--black); }

/* ══ Preview ══ */
.preview-box {
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  padding: 14px; margin-bottom: 14px;
  display: flex; justify-content: center;
}
.preview-page {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 6px; width: 100%; max-width: 220px;
  padding: 14px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.06);
  display: flex; flex-direction: column; gap: 10px;
}
.mock-header { display: flex; align-items: center; gap: 7px; }
.mock-logo { width: 20px; height: 20px; background: var(--black); border-radius: 4px; flex-shrink: 0; }
.mock-title-area { flex: 1; display: flex; flex-direction: column; gap: 4px; }
.mock-badge-block { width: 36px; height: 14px; background: #DCFCE7; border-radius: 3px; }
.mock-line { height: 5px; background: var(--border); border-radius: 100px; }
.mock-line.dark { background: #C4C4C0; }
.mock-line.sm   { height: 4px; }
.mock-line.w-30 { width: 30%; }
.mock-line.w-40 { width: 40%; }
.mock-line.w-50 { width: 50%; }
.mock-line.w-60 { width: 60%; }
.mock-divider { height: 1px; background: var(--border); }
.mock-info-row { display: flex; gap: 8px; }
.mock-info-col { flex: 1; display: flex; flex-direction: column; gap: 3px; }
.mock-chart { display: flex; gap: 10px; align-items: center; }
.mock-donut { width: 38px; height: 38px; border-radius: 50%; background: conic-gradient(#22C55E 40%, #F59E0B 65%, #E8180C 85%, #0A0A0A 100%); flex-shrink: 0; }
.mock-bars { flex: 1; display: flex; flex-direction: column; gap: 4px; }
.mock-bar { height: 5px; background: linear-gradient(90deg, var(--black), #7A7A72); border-radius: 100px; }
.mock-table { display: flex; flex-direction: column; gap: 3px; }
.mock-thead { display: flex; gap: 3px; }
.mock-th { flex: 1; height: 7px; background: var(--bg); border-radius: 2px; }
.mock-trow { display: flex; gap: 3px; }
.mock-td { flex: 1; height: 5px; background: var(--bg); border-radius: 2px; }

.report-meta { display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; }
.rm-row { display: flex; justify-content: space-between; align-items: center; }
.rm-label { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.rm-value { font-size: 12px; font-weight: 700; color: var(--black); }

.btn-generate {
  width: 100%; padding: 13px;
  background: var(--black); color: var(--white);
  border: 2px solid var(--black); border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: all 0.2s;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  letter-spacing: 0.3px;
}
.btn-generate:hover:not(:disabled) { background: var(--red); border-color: var(--red); }
.btn-generate:disabled { opacity: 0.55; cursor: not-allowed; }
.btn-generate svg { width: 16px; height: 16px; }
.gen-loading { display: flex; align-items: center; gap: 8px; }
.spin-ring {
  width: 14px; height: 14px;
  border: 2px solid rgba(255,255,255,0.3); border-top-color: white;
  border-radius: 50%; animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ══ Recent reports table ══ */
.recent-table { display: flex; flex-direction: column; }
.rt-header {
  display: grid; grid-template-columns: 2fr 1fr 1.5fr 1fr 80px;
  padding: 9px 12px; gap: 12px;
  font-size: 10px; font-weight: 700; color: var(--muted);
  text-transform: uppercase; letter-spacing: 1px;
  background: var(--bg);
  border-radius: 8px; margin-bottom: 2px;
}
.rt-row {
  display: grid; grid-template-columns: 2fr 1fr 1.5fr 1fr 80px;
  padding: 11px 12px; gap: 12px;
  border-bottom: 1px solid var(--bg); align-items: center;
  transition: background 0.15s;
}
.rt-row:hover { background: #F9F9F7; }
.rt-row:last-child { border-bottom: none; }
.rt-name { display: flex; align-items: center; gap: 10px; }
.rt-icon {
  width: 28px; height: 28px;
  display: flex; align-items: center; justify-content: center;
  background: #FEE2E2; border-radius: 7px; flex-shrink: 0;
}
.rt-icon svg { width: 15px; height: 15px; }
.rt-title { font-size: 13px; font-weight: 700; color: var(--black); }
.rt-size  { font-size: 10px; color: var(--muted); font-weight: 600; letter-spacing: 0.3px; margin-top: 1px; }
.rt-type, .rt-period, .rt-date { font-size: 12px; color: var(--muted); font-weight: 500; }

.rt-actions { display: flex; gap: 5px; }
.rt-btn {
  width: 28px; height: 28px; border-radius: 7px; border: 1.5px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: all 0.15s; background: var(--bg);
}
.rt-btn svg { width: 12px; height: 12px; }
.rt-btn.download { color: var(--black); }
.rt-btn.download:hover { background: var(--black); color: var(--white); border-color: var(--black); }
.rt-btn.delete { color: var(--muted); }
.rt-btn.delete:hover { background: var(--red); color: var(--white); border-color: var(--red); }

.rt-empty { text-align: center; padding: 32px; font-size: 13px; color: var(--muted); font-weight: 600; }

/* ══ Toast ══ */
.toast {
  position: fixed; bottom: 28px; right: 28px; z-index: 9999;
  display: flex; align-items: center; gap: 10px;
  padding: 13px 18px; border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600;
  box-shadow: 0 8px 24px rgba(0,0,0,0.2); min-width: 240px;
}
.toast svg { width: 16px; height: 16px; flex-shrink: 0; }
.toast.success { background: var(--black); color: var(--white); }
.toast.error   { background: var(--red);   color: var(--white); }
.toast-enter-active, .toast-leave-active { transition: all 0.28s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(10px); }

/* ══ Responsive ══ */
@media (max-width: 1100px) {
  .config-panel { grid-template-columns: 1fr; }
  .config-right { border-left: none; border-top: 1.5px solid var(--border); background: var(--white); }
  .report-types-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
  .report-types-grid { grid-template-columns: 1fr 1fr; }
  .config-fields { grid-template-columns: 1fr; }
  .config-field.full { grid-column: span 1; }
  .include-grid { grid-template-columns: 1fr; }
  .rt-header, .rt-row { grid-template-columns: 1fr 80px; }
  .rt-type, .rt-period, .rt-date { display: none; }
}
</style>