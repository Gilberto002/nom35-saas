<template>
  <MainLayout current-page="/results" page-title="Resultados NOM-035">
    <div class="results-page">

      <!-- ══ EMPTY STATE GLOBAL: sin evaluaciones ══ -->
      <div v-if="!isLoading && results.length === 0" class="empty-global">
        <div class="eg-illustration">
          <svg viewBox="0 0 180 180" fill="none">
            <circle cx="90" cy="90" r="80" fill="#F8FAFC"/>
            <rect x="50" y="44" width="80" height="92" rx="10" fill="white" stroke="#E2E8F0" stroke-width="2"/>
            <path d="M66 72h48M66 86h36M66 100h24M66 114h16" stroke="#E2E8F0" stroke-width="3" stroke-linecap="round"/>
            <circle cx="128" cy="128" r="26" fill="#EFF6FF" stroke="#BFDBFE" stroke-width="2"/>
            <path d="M120 128h16M128 120v16" stroke="#1D4ED8" stroke-width="2.5" stroke-linecap="round"/>
          </svg>
        </div>
        <h2 class="eg-title">Aún no hay evaluaciones registradas</h2>
        <p class="eg-sub">
          Una vez que los colaboradores completen su primera evaluación NOM-035,
          los resultados aparecerán aquí con análisis de riesgo y métricas detalladas.
        </p>
        <Link href="/evaluations" class="eg-cta">
          <svg viewBox="0 0 18 18" fill="none"><path d="M9 4v10M4 9h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
          Iniciar primera evaluación
        </Link>
      </div>

      <!-- ══ CONTENIDO PRINCIPAL ══ -->
      <template v-else>

        <!-- Summary cards -->
        <div class="summary-grid">
          <div class="summary-card">
            <div class="sc-top">
              <div class="sc-icon sc-icon--blue">
                <svg viewBox="0 0 24 24" fill="none"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 004 0M9 5a2 2 0 014 0M9 12h6M9 16h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
              </div>
              <span v-if="!isLoading" class="sc-trend sc-trend--neutral">Total</span>
              <span v-else class="sc-skeleton sc-skeleton--sm"></span>
            </div>
            <div class="sc-value" v-if="!isLoading">{{ summaryCards.total }}</div>
            <div class="sc-skeleton sc-skeleton--val" v-else></div>
            <div class="sc-label">Total Evaluaciones</div>
            <div class="sc-bar"><div class="sc-bar-fill" style="width:100%; background: #1D4ED8"></div></div>
          </div>

          <div class="summary-card">
            <div class="sc-top">
              <div class="sc-icon sc-icon--red">
                <svg viewBox="0 0 24 24" fill="none"><path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
              </div>
              <span v-if="!isLoading" class="sc-trend sc-trend--warn">{{ summaryCards.pctAlto }}% del total</span>
              <span v-else class="sc-skeleton sc-skeleton--sm"></span>
            </div>
            <div class="sc-value sc-value--red" v-if="!isLoading">{{ summaryCards.altoMuyAlto }}</div>
            <div class="sc-skeleton sc-skeleton--val" v-else></div>
            <div class="sc-label">Riesgo Alto / Muy Alto</div>
            <div class="sc-bar"><div class="sc-bar-fill sc-bar-fill--red" :style="{ width: summaryCards.pctAlto + '%' }"></div></div>
          </div>

          <div class="summary-card">
            <div class="sc-top">
              <div class="sc-icon sc-icon--green">
                <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/><path d="M8 12l3 3 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
              <span v-if="!isLoading" class="sc-trend sc-trend--up">{{ summaryCards.pctBajo }}% bajo riesgo</span>
              <span v-else class="sc-skeleton sc-skeleton--sm"></span>
            </div>
            <div class="sc-value" v-if="!isLoading">{{ summaryCards.bajo }}</div>
            <div class="sc-skeleton sc-skeleton--val" v-else></div>
            <div class="sc-label">Riesgo Bajo</div>
            <div class="sc-bar"><div class="sc-bar-fill sc-bar-fill--green" :style="{ width: summaryCards.pctBajo + '%' }"></div></div>
          </div>

          <div class="summary-card">
            <div class="sc-top">
              <div class="sc-icon sc-icon--amber">
                <svg viewBox="0 0 24 24" fill="none"><path d="M3 17l6-7 5 4 7-8M21 21H3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
              <span v-if="!isLoading" class="sc-trend sc-trend--neutral">Escala 0–60+</span>
              <span v-else class="sc-skeleton sc-skeleton--sm"></span>
            </div>
            <div class="sc-value" v-if="!isLoading">{{ summaryCards.avgScore }}</div>
            <div class="sc-skeleton sc-skeleton--val" v-else></div>
            <div class="sc-label">Puntaje Promedio</div>
            <div class="sc-bar"><div class="sc-bar-fill sc-bar-fill--amber" :style="{ width: Math.min(summaryCards.avgScore / 70 * 100, 100) + '%' }"></div></div>
          </div>
        </div>

        <!-- Filters bar -->
        <div class="filters-bar">
          <div class="filters-left">
            <div class="filter-group">
              <label class="filter-label">Área</label>
              <select v-model="filters.area" class="filter-select" @change="loadResults">
                <option value="">Todas las áreas</option>
                <option v-for="a in areas" :key="a.id" :value="a.id">{{ a.name }}</option>
              </select>
            </div>
            <div class="filter-group">
              <label class="filter-label">Guía NOM-035</label>
              <select v-model="filters.questionnaire" class="filter-select" @change="loadResults">
                <option value="">Todas las guías</option>
                <option value="1">Guía I</option>
                <option value="2">Guía II</option>
                <option value="3">Guía III</option>
              </select>
            </div>
            <div class="filter-group">
              <label class="filter-label">Nivel de riesgo</label>
              <select v-model="filters.risk" class="filter-select" @change="loadResults">
                <option value="">Todos los niveles</option>
                <option value="Bajo">Bajo</option>
                <option value="Medio">Medio</option>
                <option value="Alto">Alto</option>
                <option value="Muy Alto">Muy Alto</option>
              </select>
            </div>
          </div>
          <div class="filters-right">
            <button class="btn-clear" v-if="hasActiveFilters" @click="clearFilters">
              <svg viewBox="0 0 16 16" fill="none"><path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
              Limpiar filtros
            </button>
            <button class="export-btn" @click="exportCSV">
              <svg viewBox="0 0 20 20" fill="none">
                <path d="M10 3v10M6 9l4 4 4-4M3 15h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Exportar CSV
            </button>
            <button class="export-btn export-btn--pdf" @click="exportPDF">
              <svg viewBox="0 0 20 20" fill="none">
                <path d="M5 2h7l4 4v12a1 1 0 01-1 1H5a1 1 0 01-1-1V3a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4"/>
                <path d="M10 8v6M7 12l3 3 3-3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Exportar PDF
            </button>
          </div>
        </div>

        <!-- Charts row -->
        <div class="charts-row">

          <!-- Donut -->
          <div class="chart-card">
            <div class="chart-header">
              <p class="chart-eyebrow">ANÁLISIS</p>
              <h3 class="chart-title">Distribución de Riesgo</h3>
            </div>
            <div class="donut-wrap">
              <svg viewBox="0 0 120 120" class="donut-svg">
                <circle cx="60" cy="60" r="48" fill="none" stroke="#F1F5F9" stroke-width="14"/>
                <circle cx="60" cy="60" r="48" fill="none" stroke="#22C55E" stroke-width="14"
                  :stroke-dasharray="riskDonut.bajo.dash" stroke-dashoffset="0"
                  transform="rotate(-90 60 60)" stroke-linecap="butt"/>
                <circle cx="60" cy="60" r="48" fill="none" stroke="#F59E0B" stroke-width="14"
                  :stroke-dasharray="riskDonut.medio.dash" :stroke-dashoffset="riskDonut.medio.offset"
                  transform="rotate(-90 60 60)" stroke-linecap="butt"/>
                <circle cx="60" cy="60" r="48" fill="none" stroke="#EF4444" stroke-width="14"
                  :stroke-dasharray="riskDonut.alto.dash" :stroke-dashoffset="riskDonut.alto.offset"
                  transform="rotate(-90 60 60)" stroke-linecap="butt"/>
                <circle cx="60" cy="60" r="48" fill="none" stroke="#7C3AED" stroke-width="14"
                  :stroke-dasharray="riskDonut.muyAlto.dash" :stroke-dashoffset="riskDonut.muyAlto.offset"
                  transform="rotate(-90 60 60)" stroke-linecap="butt"/>
                <text x="60" y="55" text-anchor="middle" font-size="18" font-weight="700" fill="#1E3A5F" font-family="sans-serif">{{ totalEvaluations }}</text>
                <text x="60" y="68" text-anchor="middle" font-size="7.5" fill="#94A3B8" font-family="sans-serif" letter-spacing="1">TOTAL</text>
              </svg>
              <div class="donut-legend">
                <div v-for="item in riskLegend" :key="item.label" class="legend-item">
                  <span class="legend-swatch" :style="{ background: item.color }"></span>
                  <span class="legend-label">{{ item.label }}</span>
                  <span class="legend-count" :style="{ color: item.color }">{{ item.count }}</span>
                  <span class="legend-pct">{{ totalEvaluations ? Math.round(item.count/totalEvaluations*100) : 0 }}%</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Bar chart por área -->
          <div class="chart-card chart-wide">
            <div class="chart-header">
              <p class="chart-eyebrow">POR ÁREA</p>
              <h3 class="chart-title">Puntaje Promedio por Departamento</h3>
            </div>
            <div v-if="areaRiskData.length === 0" class="chart-empty">
              <p>Sin datos suficientes para mostrar áreas</p>
            </div>
            <div v-else class="bars-wrap">
              <div v-for="area in areaRiskData" :key="area.name" class="bar-row">
                <span class="bar-label">{{ area.name }}</span>
                <div class="bar-track">
                  <div class="bar-fill" :style="{ width: area.percent + '%', background: riskColor(area.riskLevel) }"></div>
                </div>
                <div class="bar-meta">
                  <span class="bar-score">{{ area.score }}</span>
                  <span class="risk-chip" :class="riskClass(area.riskLevel)">{{ area.riskLevel }}</span>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Results table -->
        <div class="table-card">
          <div class="table-header">
            <div>
              <p class="chart-eyebrow">EVALUACIONES</p>
              <h3 class="chart-title">Resultados Individuales</h3>
              <p class="table-sub">
                <span v-if="!isLoading">{{ filteredResults.length }} registro(s) encontrado(s)</span>
                <span v-else>Cargando…</span>
              </p>
            </div>
            <div class="table-search-wrap">
              <svg class="search-icon" viewBox="0 0 20 20" fill="none">
                <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.4"/>
                <path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
              </svg>
              <input v-model="searchQuery" type="text" placeholder="Buscar colaborador…" class="table-search"/>
            </div>
          </div>

          <div class="table-wrap">
            <table class="results-table">
              <thead>
                <tr>
                  <th @click="sortBy('employee_name')">
                    Colaborador
                    <svg viewBox="0 0 16 16" fill="none"><path d="M5 6l3-3 3 3M5 10l3 3 3-3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                  </th>
                  <th @click="sortBy('area')">Área</th>
                  <th @click="sortBy('questionnaire')">Guía</th>
                  <th @click="sortBy('score')">Puntaje</th>
                  <th @click="sortBy('risk_level')">Nivel de Riesgo</th>
                  <th>Fecha</th>
                  <th class="th-right">Acciones</th>
                </tr>
              </thead>
              <tbody>

                <!-- Loading skeleton -->
                <tr v-if="isLoading" v-for="n in 5" :key="'sk'+n" class="skeleton-row">
                  <td><div class="sk-cell"><div class="sk-avatar"></div><div class="sk-lines"><div class="sk-line sk-line--name"></div><div class="sk-line sk-line--email"></div></div></div></td>
                  <td><div class="sk-chip"></div></td>
                  <td><div class="sk-chip"></div></td>
                  <td><div class="sk-score"></div></td>
                  <td><div class="sk-badge"></div></td>
                  <td><div class="sk-line sk-line--date"></div></td>
                  <td><div class="sk-btn"></div></td>
                </tr>

                <!-- Empty state con filtros -->
                <tr v-else-if="filteredResults.length === 0 && results.length > 0">
                  <td colspan="7" class="state-row">
                    <div class="state-inner">
                      <svg viewBox="0 0 48 48" fill="none">
                        <circle cx="24" cy="24" r="20" stroke="#CBD5E1" stroke-width="2"/>
                        <path d="M16 24h16M24 16v16" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round"/>
                      </svg>
                      <p class="si-title">Sin resultados con los filtros aplicados</p>
                      <p class="si-sub">Prueba ajustando los filtros o limpiándolos para ver todos los registros.</p>
                      <button class="si-btn" @click="clearFilters">Limpiar filtros</button>
                    </div>
                  </td>
                </tr>

                <!-- Filas reales -->
                <tr v-else v-for="result in paginatedResults" :key="result.id" class="table-row">
                  <td>
                    <div class="employee-cell">
                      <div class="employee-avatar" :style="{ background: avatarGradient(result.employee_name) }">
                        {{ initials(result.employee_name) }}
                      </div>
                      <div>
                        <div class="employee-name">{{ result.employee_name }}</div>
                        <div class="employee-sub">Folio: {{ result.id }}</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="area-chip">{{ result.area }}</span></td>
                  <td><span class="guide-chip">{{ result.questionnaire }}</span></td>
                  <td>
                    <div class="score-cell">
                      <span class="score-num" :style="{ color: riskColor(result.risk_level) }">{{ result.score }}</span>
                      <div class="score-bar-mini">
                        <div class="score-fill-mini" :style="{ width: Math.min(result.score / 70 * 100, 100) + '%', background: riskColor(result.risk_level) }"></div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="risk-badge" :class="riskClass(result.risk_level)">
                      <span class="risk-dot"></span>
                      {{ result.risk_level }}
                    </span>
                  </td>
                  <td class="date-cell">{{ formatDate(result.finished_at) }}</td>
                  <td class="td-right">
                    <button class="view-btn" @click="viewDetail(result)">
                      <svg viewBox="0 0 16 16" fill="none"><path d="M2 8s2.5-5 6-5 6 5 6 5-2.5 5-6 5-6-5-6-5z" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.3"/></svg>
                      Ver detalle
                    </button>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="pagination" v-if="filteredResults.length > 0">
            <span class="page-info">
              {{ (currentPage - 1) * pageSize + 1 }}–{{ Math.min(currentPage * pageSize, filteredResults.length) }}
              de {{ filteredResults.length }} registros
            </span>
            <div class="page-controls">
              <button class="page-btn" :disabled="currentPage === 1" @click="currentPage--">
                <svg viewBox="0 0 16 16" fill="none"><path d="M10 4L6 8l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              </button>
              <button
                v-for="p in totalPages" :key="p"
                class="page-num" :class="{ active: p === currentPage }"
                @click="currentPage = p">{{ p }}</button>
              <button class="page-btn" :disabled="currentPage === totalPages" @click="currentPage++">
                <svg viewBox="0 0 16 16" fill="none"><path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              </button>
            </div>
          </div>
        </div>

      </template>

    </div>
  </MainLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import MainLayout from '../Layouts/MainLayout.vue'
import axios from 'axios'

// ── State ──
const isLoading   = ref(false)
const searchQuery = ref('')
const currentPage = ref(1)
const pageSize    = 10
const sortKey     = ref('finished_at')
const sortDir     = ref('desc')
const filters     = reactive({ area: '', questionnaire: '', risk: '' })

const areas   = ref([])
const results = ref([])

// ── Computed: KPIs ──
const summaryCards = computed(() => {
  const r     = results.value
  const total = r.length
  const altoMuyAlto = r.filter(x => x.risk_level === 'Alto' || x.risk_level === 'Muy Alto').length
  const bajo        = r.filter(x => x.risk_level === 'Bajo').length
  const avgScore    = total ? Math.round(r.reduce((a, b) => a + b.score, 0) / total) : 0
  return {
    total,
    altoMuyAlto,
    bajo,
    avgScore,
    pctAlto: total ? Math.round(altoMuyAlto / total * 100) : 0,
    pctBajo: total ? Math.round(bajo / total * 100) : 0,
  }
})

// ── Computed: Donut ──
const circumference   = 2 * Math.PI * 48
const totalEvaluations = computed(() => results.value.length)

const riskCounts = computed(() => ({
  bajo:    results.value.filter(r => r.risk_level === 'Bajo').length,
  medio:   results.value.filter(r => r.risk_level === 'Medio').length,
  alto:    results.value.filter(r => r.risk_level === 'Alto').length,
  muyAlto: results.value.filter(r => r.risk_level === 'Muy Alto').length,
}))

const riskDonut = computed(() => {
  const t = totalEvaluations.value || 1
  const c = circumference
  const b  = riskCounts.value.bajo    / t * c
  const m  = riskCounts.value.medio   / t * c
  const a  = riskCounts.value.alto    / t * c
  const ma = riskCounts.value.muyAlto / t * c
  return {
    bajo:    { dash: `${b} ${c - b}` },
    medio:   { dash: `${m} ${c - m}`,   offset: -b },
    alto:    { dash: `${a} ${c - a}`,   offset: -(b + m) },
    muyAlto: { dash: `${ma} ${c - ma}`, offset: -(b + m + a) },
  }
})

const riskLegend = computed(() => [
  { label: 'Bajo',     color: '#22C55E', count: riskCounts.value.bajo    },
  { label: 'Medio',    color: '#F59E0B', count: riskCounts.value.medio   },
  { label: 'Alto',     color: '#EF4444', count: riskCounts.value.alto    },
  { label: 'Muy Alto', color: '#7C3AED', count: riskCounts.value.muyAlto },
])

// ── Computed: Áreas ──
const areaRiskData = computed(() => {
  // Construir áreas dinámicamente desde los resultados
  const map = {}
  results.value.forEach(r => {
    const k = r.area || '—'
    if (!map[k]) map[k] = { name: k, scores: [] }
    map[k].scores.push(r.score)
  })
  return Object.values(map).map(a => {
    const avg = Math.round(a.scores.reduce((s, v) => s + v, 0) / a.scores.length)
    return { name: a.name, score: avg, riskLevel: scoreToRisk(avg), percent: Math.min(avg / 70 * 100, 100) }
  }).sort((a, b) => b.score - a.score)
})

// ── Computed: Filtros y tabla ──
const hasActiveFilters = computed(() => filters.area || filters.questionnaire || filters.risk || searchQuery.value)

const filteredResults = computed(() => {
  let data = [...results.value]
  if (filters.area) {
    const aName = areas.value.find(a => a.id == filters.area)?.name
    if (aName) data = data.filter(r => r.area === aName)
  }
  if (filters.risk) data = data.filter(r => r.risk_level === filters.risk)
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    data = data.filter(r => r.employee_name?.toLowerCase().includes(q))
  }
  data.sort((a, b) => {
    const av = a[sortKey.value], bv = b[sortKey.value]
    return sortDir.value === 'asc' ? (av > bv ? 1 : -1) : (av < bv ? 1 : -1)
  })
  return data
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredResults.value.length / pageSize)))
const paginatedResults = computed(() => {
  const start = (currentPage.value - 1) * pageSize
  return filteredResults.value.slice(start, start + pageSize)
})

// ── Methods ──
async function loadResults() {
  isLoading.value = true
  try {
    const params = {}
    if (filters.area)          params.area_id          = filters.area
    if (filters.questionnaire) params.questionnaire_id = filters.questionnaire
    if (filters.risk)          params.risk_level       = filters.risk

    const { data } = await axios.get('/api/results', { params })

    results.value = (data.data || data).map(r => ({
      ...r,
      // Normalizar campos para que la vista los entienda
      employee_name: r.employee_name ?? `${r.first_name ?? ''} ${r.last_name ?? ''}`.trim(),
      area:          r.area          ?? r.area_name ?? '—',
      questionnaire: r.questionnaire ?? r.guide     ?? '—',
      finished_at:   r.finished_at   ?? r.date      ?? null,
    }))

    // Extraer áreas únicas de los resultados si no hay endpoint de áreas
    const uniqueAreas = [...new Map(results.value.map(r => [r.area, { id: r.area, name: r.area }])).values()]
    if (areas.value.length === 0) areas.value = uniqueAreas

  } catch (e) {
    console.error('Error cargando resultados:', e)
    results.value = []
  } finally {
    isLoading.value = false
  }
}

async function loadAreas() {
  try {
    const { data } = await axios.get('/api/areas')
    areas.value = data.data || data
  } catch {
    // Se poblará desde los resultados
  }
}

function clearFilters() {
  filters.area = ''
  filters.questionnaire = ''
  filters.risk = ''
  searchQuery.value = ''
  currentPage.value = 1
}

function sortBy(key) {
  if (sortKey.value === key) sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc'
  else { sortKey.value = key; sortDir.value = 'asc' }
}

function viewDetail(result) {
  // Implementar modal o navegación al detalle
  console.log('Ver detalle:', result.id)
}

function exportCSV() {
  if (filteredResults.value.length === 0) return
  const header = 'Folio,Colaborador,Área,Guía,Puntaje,Nivel Riesgo,Fecha'
  const rows = filteredResults.value.map(r =>
    `${r.id},"${r.employee_name}","${r.area}","${r.questionnaire}",${r.score},"${r.risk_level}","${formatDate(r.finished_at)}"`
  )
  const blob = new Blob([[header, ...rows].join('\n')], { type: 'text/csv;charset=utf-8;' })
  const a = document.createElement('a')
  a.href = URL.createObjectURL(blob)
  a.download = `NOM035_resultados_${new Date().toISOString().slice(0, 10)}.csv`
  a.click()
}

function exportPDF() {
  const params = new URLSearchParams()
  params.set('type', 'company')
  if (filters.area)          params.set('area_id',          filters.area)
  if (filters.questionnaire) params.set('questionnaire_id', filters.questionnaire)
  if (filters.risk)          params.set('risk_level',       filters.risk)
  window.open(`/api/reports/generate?${params.toString()}`, '_blank')
}

// ── Helpers ──
function scoreToRisk(score) {
  if (score < 20) return 'Bajo'
  if (score < 45) return 'Medio'
  if (score < 70) return 'Alto'
  return 'Muy Alto'
}
function riskColor(level) {
  return { Bajo: '#22C55E', Medio: '#F59E0B', Alto: '#EF4444', 'Muy Alto': '#7C3AED' }[level] || '#94A3B8'
}
function riskClass(level) {
  return { Bajo: 'risk-bajo', Medio: 'risk-medio', Alto: 'risk-alto', 'Muy Alto': 'risk-muyalto' }[level] || ''
}
function initials(name) {
  return (name ?? '').split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase() || '??'
}
const gradients = [
  'linear-gradient(135deg,#1D4ED8,#38BDF8)',
  'linear-gradient(135deg,#7C3AED,#C084FC)',
  'linear-gradient(135deg,#059669,#34D399)',
  'linear-gradient(135deg,#B45309,#FCD34D)',
  'linear-gradient(135deg,#BE185D,#F472B6)',
]
function avatarGradient(name) {
  return gradients[((name ?? '').charCodeAt(0) || 0) % gradients.length]
}
function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('es-MX', { day: '2-digit', month: 'short', year: 'numeric' })
}

onMounted(() => {
  loadAreas()
  loadResults()
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600;700&display=swap');
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.results-page {
  display: flex; flex-direction: column; gap: 20px;
  font-family: 'DM Sans', sans-serif;
  --black:  #0F172A;
  --bg:     #F8FAFC;
  --white:  #ffffff;
  --muted:  #64748B;
  --border: #E2E8F0;
  --radius: 14px;
}

/* ══ EMPTY GLOBAL ══ */
.empty-global {
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; gap: 20px; padding: 80px 24px;
  text-align: center;
}
.eg-illustration svg { width: 180px; height: 180px; }
.eg-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 28px; letter-spacing: 1px; color: var(--black);
}
.eg-sub { font-size: 14px; color: var(--muted); line-height: 1.7; max-width: 440px; }
.eg-cta {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 24px; border-radius: 12px;
  background: linear-gradient(135deg, #1D4ED8, #0EA5E9);
  color: white; text-decoration: none;
  font-size: 14px; font-weight: 700; transition: all 0.2s;
}
.eg-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(29,78,216,0.3); }
.eg-cta svg { width: 16px; height: 16px; }

/* ══ SUMMARY CARDS ══ */
.summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }

.summary-card {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: var(--radius); padding: 20px;
  position: relative; overflow: hidden;
  transition: transform 0.2s, box-shadow 0.2s;
}
.summary-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(15,23,42,0.07); }
.summary-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
  border-radius: var(--radius) var(--radius) 0 0;
}
.summary-card:nth-child(1)::before { background: #1D4ED8; }
.summary-card:nth-child(2)::before { background: #EF4444; }
.summary-card:nth-child(3)::before { background: #22C55E; }
.summary-card:nth-child(4)::before { background: #F59E0B; }

.sc-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
.sc-icon {
  width: 36px; height: 36px; border-radius: 9px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.sc-icon svg { width: 18px; height: 18px; }
.sc-icon--blue  { background: #EFF6FF; color: #1D4ED8; }
.sc-icon--red   { background: #FEF2F2; color: #EF4444; }
.sc-icon--green { background: #F0FDF4; color: #15803D; }
.sc-icon--amber { background: #FFFBEB; color: #B45309; }

.sc-trend { font-size: 10px; font-weight: 700; padding: 3px 9px; border-radius: 100px; white-space: nowrap; }
.sc-trend--up      { background: #DCFCE7; color: #15803D; }
.sc-trend--warn    { background: #FEF2F2; color: #EF4444; }
.sc-trend--neutral { background: var(--bg); color: var(--muted); }

.sc-value { font-family: 'Bebas Neue', sans-serif; font-size: 48px; line-height: 0.9; color: var(--black); margin-bottom: 4px; }
.sc-value--red { color: #EF4444; }
.sc-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); margin-bottom: 14px; }
.sc-bar { height: 3px; background: var(--border); border-radius: 100px; overflow: hidden; }
.sc-bar-fill { height: 100%; border-radius: 100px; transition: width 1s ease; }
.sc-bar-fill--red   { background: #EF4444; }
.sc-bar-fill--green { background: #22C55E; }
.sc-bar-fill--amber { background: #F59E0B; }

/* Skeleton loaders en cards */
.sc-skeleton { display: inline-block; background: var(--border); border-radius: 6px; animation: shimmer 1.4s ease-in-out infinite; }
.sc-skeleton--sm  { width: 70px; height: 18px; }
.sc-skeleton--val { width: 60px; height: 44px; margin-bottom: 4px; }

/* ══ FILTERS BAR ══ */
.filters-bar {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: var(--radius); padding: 16px 20px;
  display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
.filters-left  { display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap; }
.filters-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
.filter-group  { display: flex; flex-direction: column; gap: 5px; }
.filter-label  { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--black); }
.filter-select {
  padding: 8px 30px 8px 11px; border: 1.5px solid var(--border); border-radius: 9px;
  font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--black);
  background: var(--bg) url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M4 6l4 4 4-4' stroke='%2364748B' stroke-width='1.4' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 8px center;
  appearance: none; cursor: pointer; outline: none; transition: border-color 0.2s;
}
.filter-select:focus { border-color: #1D4ED8; background-color: var(--white); }

.btn-clear {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 14px; border-radius: 9px; border: 1.5px solid var(--border);
  background: var(--bg); color: var(--muted);
  font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 700;
  cursor: pointer; transition: all 0.2s;
}
.btn-clear:hover { border-color: #EF4444; color: #EF4444; background: #FEF2F2; }
.btn-clear svg { width: 13px; height: 13px; }

.export-btn {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 9px 16px; background: var(--black); color: white;
  border: 2px solid var(--black); border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: all 0.2s; white-space: nowrap;
}
.export-btn:hover { background: #1D4ED8; border-color: #1D4ED8; }
.export-btn--pdf:hover { background: #EF4444; border-color: #EF4444; }
.export-btn svg { width: 15px; height: 15px; }

/* ══ CHARTS ROW ══ */
.charts-row { display: grid; grid-template-columns: 280px 1fr; gap: 16px; }

.chart-card {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: var(--radius); padding: 24px;
}
.chart-header { margin-bottom: 20px; }
.chart-eyebrow { font-size: 9px; font-weight: 700; letter-spacing: 2.5px; color: #1D4ED8; margin-bottom: 3px; }
.chart-title   { font-family: 'Bebas Neue', sans-serif; font-size: 18px; letter-spacing: 0.5px; color: var(--black); }

.chart-empty { display: flex; align-items: center; justify-content: center; height: 120px; }
.chart-empty p { font-size: 13px; color: var(--muted); }

/* Donut */
.donut-wrap   { display: flex; flex-direction: column; align-items: center; gap: 18px; }
.donut-svg    { width: 130px; height: 130px; }
.donut-legend { width: 100%; display: flex; flex-direction: column; gap: 8px; }
.legend-item  { display: flex; align-items: center; gap: 8px; }
.legend-swatch { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.legend-label  { flex: 1; font-size: 12px; font-weight: 600; color: var(--black); }
.legend-count  { font-family: 'Bebas Neue', sans-serif; font-size: 18px; line-height: 1; }
.legend-pct    { font-size: 10px; color: var(--muted); font-weight: 700; min-width: 30px; text-align: right; }

/* Bar chart */
.bars-wrap { display: flex; flex-direction: column; gap: 16px; }
.bar-row   { display: flex; align-items: center; gap: 12px; }
.bar-label { width: 140px; font-size: 12px; font-weight: 600; color: var(--black); flex-shrink: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.bar-track { flex: 1; height: 8px; background: var(--bg); border-radius: 100px; overflow: hidden; }
.bar-fill  { height: 100%; border-radius: 100px; transition: width 0.8s cubic-bezier(0.4,0,0.2,1); }
.bar-meta  { display: flex; align-items: center; gap: 8px; }
.bar-score { font-family: 'Bebas Neue', sans-serif; font-size: 20px; color: var(--black); width: 28px; text-align: right; line-height: 1; }

/* ══ RISK CHIPS ══ */
.risk-chip, .risk-badge {
  font-size: 10px; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.5px; padding: 3px 9px; border-radius: 100px; white-space: nowrap;
}
.risk-badge   { display: inline-flex; align-items: center; gap: 5px; }
.risk-dot     { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
.risk-bajo    { background: #DCFCE7; color: #15803D; }
.risk-medio   { background: #FEF9C3; color: #B45309; }
.risk-alto    { background: #FEF2F2; color: #DC2626; }
.risk-muyalto { background: #EDE9FE; color: #7C3AED; }

/* ══ TABLE ══ */
.table-card { background: var(--white); border: 1.5px solid var(--border); border-radius: var(--radius); overflow: hidden; }
.table-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  padding: 20px 24px; border-bottom: 1.5px solid var(--border); gap: 16px;
}
.table-sub { font-size: 11px; color: var(--muted); margin-top: 3px; font-weight: 600; }

.table-search-wrap { position: relative; align-self: flex-end; }
.search-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: var(--muted); pointer-events: none; }
.table-search {
  padding: 9px 14px 9px 34px; border: 1.5px solid var(--border); border-radius: 9px;
  font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--black);
  background: var(--bg); outline: none; width: 220px; transition: all 0.2s;
}
.table-search:focus { border-color: #1D4ED8; background: var(--white); }
.table-search::placeholder { color: #CBD5E1; }

.table-wrap { overflow-x: auto; }
.results-table { width: 100%; border-collapse: collapse; }
.results-table thead tr { background: var(--bg); }
.results-table th {
  padding: 11px 16px; text-align: left;
  font-size: 10px; font-weight: 700; color: var(--muted);
  text-transform: uppercase; letter-spacing: 1px;
  border-bottom: 1.5px solid var(--border);
  cursor: pointer; white-space: nowrap; user-select: none; transition: color 0.15s;
}
.results-table th:hover { color: var(--black); }
.results-table th svg { width: 11px; height: 11px; margin-left: 4px; vertical-align: middle; opacity: 0.4; }
.th-right { text-align: right; padding-right: 20px !important; }
.td-right  { text-align: right; padding-right: 16px !important; }

.table-row { border-bottom: 1px solid var(--bg); transition: background 0.15s; }
.table-row:last-child { border-bottom: none; }
.table-row:hover { background: #F8FBFF; }
.results-table td { padding: 13px 16px; font-size: 13px; color: var(--muted); vertical-align: middle; }

/* State row */
.state-row { padding: 0 !important; }
.state-inner {
  display: flex; flex-direction: column; align-items: center;
  gap: 10px; padding: 56px 24px; text-align: center;
}
.state-inner svg { width: 44px; height: 44px; }
.si-title { font-size: 15px; font-weight: 700; color: var(--black); }
.si-sub   { font-size: 13px; color: var(--muted); max-width: 360px; line-height: 1.6; }
.si-btn {
  margin-top: 4px; padding: 8px 20px; border-radius: 9px;
  border: 1.5px solid var(--border); background: var(--bg); color: var(--black);
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: all 0.2s;
}
.si-btn:hover { background: #EFF6FF; border-color: #BFDBFE; color: #1D4ED8; }

/* Employee cell */
.employee-cell  { display: flex; align-items: center; gap: 10px; }
.employee-avatar {
  width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 800; color: white;
}
.employee-name { font-size: 13px; font-weight: 700; color: var(--black); }
.employee-sub  { font-size: 10.5px; color: #CBD5E1; margin-top: 1px; font-family: 'Courier New', monospace; }

.area-chip  {
  background: var(--bg); color: var(--black); font-size: 10px; font-weight: 700;
  padding: 3px 9px; border-radius: 100px; border: 1px solid var(--border);
  white-space: nowrap; text-transform: uppercase; letter-spacing: 0.4px;
}
.guide-chip {
  background: var(--bg); color: var(--muted); font-size: 10px; font-weight: 700;
  padding: 3px 9px; border-radius: 100px; border: 1px solid var(--border); white-space: nowrap;
}

.score-cell     { display: flex; flex-direction: column; gap: 4px; }
.score-num      { font-family: 'Bebas Neue', sans-serif; font-size: 22px; line-height: 1; }
.score-bar-mini { width: 56px; height: 3px; background: var(--border); border-radius: 100px; overflow: hidden; }
.score-fill-mini { height: 100%; border-radius: 100px; transition: width 0.6s; }

.date-cell { white-space: nowrap; font-size: 12px; }

.view-btn {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 12px; border-radius: 7px; border: 1.5px solid var(--border);
  background: var(--bg); color: var(--black);
  font-family: 'DM Sans', sans-serif; font-size: 11px; font-weight: 700;
  cursor: pointer; transition: all 0.15s; text-transform: uppercase; letter-spacing: 0.3px;
}
.view-btn:hover { background: var(--black); color: white; border-color: var(--black); }
.view-btn svg { width: 12px; height: 12px; }

/* ══ SKELETON ROWS ══ */
.skeleton-row td { padding: 13px 16px; }
.sk-cell   { display: flex; align-items: center; gap: 10px; }
.sk-lines  { display: flex; flex-direction: column; gap: 6px; }
.sk-avatar { width: 34px; height: 34px; border-radius: 9px; background: var(--border); animation: shimmer 1.4s ease-in-out infinite; }
.sk-chip   { width: 70px; height: 22px; border-radius: 100px; background: var(--border); animation: shimmer 1.4s ease-in-out infinite; }
.sk-score  { width: 40px; height: 26px; border-radius: 6px; background: var(--border); animation: shimmer 1.4s ease-in-out infinite; }
.sk-badge  { width: 60px; height: 20px; border-radius: 100px; background: var(--border); animation: shimmer 1.4s ease-in-out infinite; }
.sk-btn    { width: 80px; height: 26px; border-radius: 7px; background: var(--border); animation: shimmer 1.4s ease-in-out infinite; margin-left: auto; }
.sk-line   { height: 10px; border-radius: 6px; background: var(--border); animation: shimmer 1.4s ease-in-out infinite; }
.sk-line--name  { width: 110px; }
.sk-line--email { width: 80px; }
.sk-line--date  { width: 70px; }

@keyframes shimmer {
  0%   { opacity: 1; }
  50%  { opacity: 0.45; }
  100% { opacity: 1; }
}

/* ══ PAGINATION ══ */
.pagination {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 24px; border-top: 1.5px solid var(--border);
}
.page-info { font-size: 11px; color: var(--muted); font-weight: 600; }
.page-controls { display: flex; align-items: center; gap: 3px; }
.page-btn {
  width: 28px; height: 28px; border: 1.5px solid var(--border); background: var(--white);
  border-radius: 6px; cursor: pointer; color: var(--muted);
  display: flex; align-items: center; justify-content: center; transition: all 0.15s;
}
.page-btn:hover:not(:disabled) { border-color: #1D4ED8; color: #1D4ED8; }
.page-btn:disabled { opacity: 0.35; cursor: not-allowed; }
.page-btn svg { width: 12px; height: 12px; }
.page-num {
  width: 28px; height: 28px; border: 1.5px solid var(--border); background: var(--white);
  border-radius: 6px; cursor: pointer; color: var(--muted);
  font-size: 12px; font-weight: 700; transition: all 0.15s;
}
.page-num:hover:not(.active) { border-color: #1D4ED8; color: #1D4ED8; }
.page-num.active { background: #1D4ED8; border-color: #1D4ED8; color: white; }

/* ══ RESPONSIVE ══ */
@media (max-width: 1100px) {
  .summary-grid { grid-template-columns: repeat(2, 1fr); }
  .charts-row   { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
  .summary-grid  { grid-template-columns: 1fr; }
  .filters-bar   { flex-direction: column; align-items: stretch; }
  .filters-left  { flex-direction: column; }
  .filters-right { flex-direction: column; align-items: stretch; }
  .export-btn    { width: 100%; justify-content: center; }
}
</style>