<template>
  <MainLayout current-page="/areas" page-title="Áreas">
    <div class="areas-page">

      <!-- Header -->
      <div class="page-header">
        <div class="search-wrap">
          <svg class="search-icon" viewBox="0 0 20 20" fill="none">
            <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.4"/>
            <path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
          </svg>
          <input v-model="searchQuery" type="text" placeholder="Buscar área..." class="search-input"/>
          <button v-if="searchQuery" class="search-clear" @click="searchQuery = ''">
            <svg viewBox="0 0 16 16" fill="none"><path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
          </button>
        </div>
        <button class="btn-primary" @click="openModal()">
          <svg viewBox="0 0 20 20" fill="none"><path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Nueva Área
        </button>
      </div>

      <!-- Stats strip -->
      <div class="stats-strip">
        <div class="strip-stat">
          <span class="strip-num">{{ areas.length }}</span>
          <span class="strip-label">Áreas registradas</span>
        </div>
        <div class="strip-divider"></div>
        <div class="strip-stat">
          <span class="strip-num">{{ totalEmployees }}</span>
          <span class="strip-label">Empleados en total</span>
        </div>
        <div class="strip-divider"></div>
        <div class="strip-stat">
          <span class="strip-num">{{ areasWithRisk }}</span>
          <span class="strip-label">Con riesgo evaluado</span>
        </div>
        <div class="strip-divider"></div>
        <div class="strip-stat">
          <span class="strip-num">{{ highRiskAreas }}</span>
          <span class="strip-label">Riesgo alto/muy alto</span>
        </div>
      </div>

      <!-- Areas grid -->
      <div v-if="isLoading" class="loading-state">
        <div class="loading-spinner"></div>
        <span>Cargando áreas...</span>
      </div>

      <div v-else-if="filteredAreas.length === 0" class="empty-state">
        <svg viewBox="0 0 64 64" fill="none">
          <path d="M8 56V24l24-16 24 16v32H8z" stroke="#CBD5E1" stroke-width="2.5" stroke-linejoin="round"/>
          <path d="M24 56V36h16v20" stroke="#CBD5E1" stroke-width="2.5" stroke-linejoin="round"/>
        </svg>
        <p class="empty-title">{{ searchQuery ? 'Sin resultados' : 'Sin áreas registradas' }}</p>
        <p class="empty-sub">{{ searchQuery ? 'Intenta otra búsqueda' : 'Crea la primera área de tu empresa' }}</p>
        <button v-if="!searchQuery" class="btn-primary" @click="openModal()">+ Nueva área</button>
      </div>

      <div v-else class="areas-grid">
        <div v-for="area in filteredAreas" :key="area.id" class="area-card">
          <!-- Card top color bar -->
          <div class="card-bar" :style="{ background: areaColor(area.id) }"></div>

          <div class="card-body">
            <div class="card-head">
              <div class="area-icon" :style="{ background: areaColorLight(area.id), color: areaColorDark(area.id) }">
                {{ area.name.charAt(0).toUpperCase() }}
              </div>
              <div class="card-actions">
                <button class="icon-btn edit" @click="openModal(area)" title="Editar">
                  <svg viewBox="0 0 16 16" fill="none"><path d="M11 2l3 3-8 8H3v-3l8-8z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                </button>
                <button class="icon-btn delete" @click="confirmDelete(area)" title="Eliminar">
                  <svg viewBox="0 0 16 16" fill="none"><path d="M3 4h10M6 4V3h4v1M5 4v8a1 1 0 001 1h4a1 1 0 001-1V4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                </button>
              </div>
            </div>

            <h3 class="area-name">{{ area.name }}</h3>
            <p class="area-company">{{ companyName }}</p>

            <!-- Metrics -->
            <div class="area-metrics">
              <div class="metric">
                <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                <span>{{ area.employees_count || 0 }} empleados</span>
              </div>
              <div class="metric">
                <svg viewBox="0 0 16 16" fill="none"><path d="M3 12l3-4 3 2 4-6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span>{{ area.evaluations_count || 0 }} evaluaciones</span>
              </div>
            </div>

            <!-- Risk level -->
            <div class="area-footer">
              <span v-if="area.avg_risk_level" class="risk-chip" :class="riskClass(area.avg_risk_level)">
                <span class="risk-dot"></span>
                {{ area.avg_risk_level }}
              </span>
              <span v-else class="risk-chip r-none">
                Sin evaluar
              </span>

              <Link :href="`/employees?area=${area.id}`" class="view-employees">
                Ver empleados →
              </Link>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- ══ Modal Crear/Editar ══ -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showModal" class="modal-overlay" @mousedown.self="closeModal">
          <div class="modal-box">
            <div class="modal-header">
              <div class="modal-icon">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                  <path d="M9 22V12h6v10" stroke="currentColor" stroke-width="1.5"/>
                </svg>
              </div>
              <div>
                <h2 class="modal-title">{{ editMode ? 'Editar Área' : 'Nueva Área' }}</h2>
                <p class="modal-sub">{{ editMode ? 'Actualiza el nombre del área' : 'Agrega un nuevo departamento a tu empresa' }}</p>
              </div>
              <button class="modal-close" @click="closeModal">
                <svg viewBox="0 0 20 20" fill="none"><path d="M5 5l10 10M15 5L5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
              </button>
            </div>

            <div class="modal-body">
              <div class="form-field" :class="{ 'has-error': formError }">
                <label class="form-label">Nombre del área <span class="required">*</span></label>
                <input
                  v-model="form.name"
                  type="text"
                  placeholder="Ej. Recursos Humanos, Operaciones, TI..."
                  class="form-input"
                  @keyup.enter="saveArea"
                  ref="nameInput"
                />
                <span v-if="formError" class="form-error">{{ formError }}</span>
              </div>

              <!-- Preview -->
              <div v-if="form.name" class="preview-area">
                <div class="preview-icon" :style="{ background: areaColorLight(99), color: areaColorDark(99) }">
                  {{ form.name.charAt(0).toUpperCase() }}
                </div>
                <div>
                  <p class="preview-name">{{ form.name }}</p>
                  <p class="preview-meta">{{ companyName }} · 0 empleados</p>
                </div>
              </div>

              <!-- Suggestions -->
              <div class="suggestions">
                <p class="sug-label">Sugerencias comunes:</p>
                <div class="sug-chips">
                  <button v-for="s in suggestions.filter(s => !areas.map(a=>a.name).includes(s))"
                    :key="s" class="sug-chip" @click="form.name = s">
                    {{ s }}
                  </button>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button class="btn-ghost" @click="closeModal" :disabled="isSaving">Cancelar</button>
              <button class="btn-primary" @click="saveArea" :disabled="isSaving">
                <span v-if="!isSaving">{{ editMode ? 'Guardar cambios' : 'Crear área' }}</span>
                <span v-else class="dots"><span></span><span></span><span></span></span>
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ══ Modal Confirmar eliminación ══ -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showDeleteModal" class="modal-overlay" @mousedown.self="showDeleteModal = false">
          <div class="modal-box modal-sm">
            <div class="delete-icon">
              <svg viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="28" fill="#FEF2F2" stroke="#FECACA" stroke-width="2"/>
                <path d="M22 22l20 20M42 22L22 42" stroke="#EF4444" stroke-width="2.5" stroke-linecap="round"/>
              </svg>
            </div>
            <h3 class="delete-title">¿Eliminar área?</h3>
            <p class="delete-sub">
              Se eliminará el área <strong>{{ deleteTarget?.name }}</strong>.
              <span v-if="deleteTarget?.employees_count > 0" class="delete-warn">
                ⚠️ Esta área tiene {{ deleteTarget.employees_count }} empleado(s) asignados.
              </span>
            </p>
            <div class="modal-footer modal-footer-center">
              <button class="btn-ghost" @click="showDeleteModal = false">Cancelar</button>
              <button class="btn-danger" @click="deleteArea" :disabled="isSaving">
                <span v-if="!isSaving">Eliminar</span>
                <span v-else class="dots"><span></span><span></span><span></span></span>
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

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
import { ref, reactive, computed, onMounted, nextTick } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import MainLayout from '../Layouts/MainLayout.vue'
import axios from 'axios'

const page = usePage()
const companyName = computed(() => page.props.auth?.company?.name || 'Mi Empresa')

// ── State ──
const isLoading = ref(false)
const isSaving  = ref(false)
const searchQuery = ref('')
const showModal = ref(false)
const showDeleteModal = ref(false)
const editMode = ref(false)
const deleteTarget = ref(null)
const nameInput = ref(null)
let editingId = null

const form = reactive({ name: '' })
const formError = ref('')
const toast = reactive({ show: false, message: '', type: 'success' })

// ── Data ──
const areas = ref([])

const suggestions = [
  'Recursos Humanos', 'Operaciones', 'Ventas', 'TI', 'Finanzas',
  'Producción', 'Logística', 'Marketing', 'Administración', 'Dirección',
]

// ── Computed ──
const filteredAreas = computed(() => {
  if (!searchQuery.value) return areas.value
  const q = searchQuery.value.toLowerCase()
  return areas.value.filter(a => a.name.toLowerCase().includes(q))
})
const totalEmployees = computed(() => areas.value.reduce((s, a) => s + (a.employees_count || 0), 0))
const areasWithRisk  = computed(() => areas.value.filter(a => a.avg_risk_level).length)
const highRiskAreas  = computed(() => areas.value.filter(a => ['Alto','Muy Alto'].includes(a.avg_risk_level)).length)

// ── Colors per area ──
const colorPalette = [
  { bg: '#DBEAFE', text: '#1D4ED8', bar: '#3B82F6' },
  { bg: '#D1FAE5', text: '#065F46', bar: '#10B981' },
  { bg: '#FEF3C7', text: '#92400E', bar: '#F59E0B' },
  { bg: '#FCE7F3', text: '#9D174D', bar: '#EC4899' },
  { bg: '#EDE9FE', text: '#4C1D95', bar: '#8B5CF6' },
  { bg: '#FFEDD5', text: '#9A3412', bar: '#F97316' },
  { bg: '#CFFAFE', text: '#164E63', bar: '#06B6D4' },
]
function colorOf(id)      { return colorPalette[id % colorPalette.length] }
function areaColor(id)     { return colorOf(id).bar }
function areaColorLight(id){ return colorOf(id).bg }
function areaColorDark(id) { return colorOf(id).text }

function riskClass(level) {
  return { Bajo:'r-bajo', Medio:'r-medio', Alto:'r-alto', 'Muy Alto':'r-muyalto' }[level] || ''
}

// ── API ──
async function fetchAreas() {
  isLoading.value = true
  try {
    const { data } = await axios.get('/api/areas')
    areas.value = data.data || data
  } catch {
    // Demo data
    areas.value = [
      { id:1, name:'Recursos Humanos', employees_count:8,  evaluations_count:6,  avg_risk_level:'Bajo'     },
      { id:2, name:'Operaciones',      employees_count:14, evaluations_count:10, avg_risk_level:'Alto'     },
      { id:3, name:'Ventas',           employees_count:11, evaluations_count:8,  avg_risk_level:'Medio'    },
      { id:4, name:'TI',               employees_count:8,  evaluations_count:5,  avg_risk_level:'Muy Alto' },
      { id:5, name:'Finanzas',         employees_count:7,  evaluations_count:2,  avg_risk_level:'Medio'    },
    ]
  } finally {
    isLoading.value = false
  }
}

async function saveArea() {
  formError.value = ''
  if (!form.name.trim()) { formError.value = 'El nombre es requerido'; return }
  if (areas.value.some(a => a.name.toLowerCase() === form.name.toLowerCase() && a.id !== editingId)) {
    formError.value = 'Ya existe un área con ese nombre'; return
  }

  isSaving.value = true
  try {
    if (editMode.value) {
      await axios.put(`/api/areas/${editingId}`, { name: form.name })
      showToast('Área actualizada correctamente')
    } else {
      await axios.post('/api/areas', { name: form.name })
      showToast('Área creada correctamente')
    }
    closeModal()
    await fetchAreas()
  } catch (e) {
    if (e.response?.status === 422) {
      formError.value = e.response.data.errors?.name?.[0] || 'Error de validación'
    } else {
      showToast('Error al guardar el área', 'error')
    }
  } finally {
    isSaving.value = false
  }
}

async function deleteArea() {
  if (!deleteTarget.value) return
  isSaving.value = true
  try {
    await axios.delete(`/api/areas/${deleteTarget.value.id}`)
    showToast('Área eliminada')
    showDeleteModal.value = false
    await fetchAreas()
  } catch {
    showToast('Error al eliminar el área', 'error')
  } finally {
    isSaving.value = false
  }
}

// ── Modal ──
function openModal(area = null) {
  form.name = ''
  formError.value = ''
  if (area) {
    editMode.value = true
    editingId = area.id
    form.name = area.name
  } else {
    editMode.value = false
    editingId = null
  }
  showModal.value = true
  nextTick(() => nameInput.value?.focus())
}

function closeModal() {
  showModal.value = false
  form.name = ''
  formError.value = ''
}

function confirmDelete(area) {
  deleteTarget.value = area
  showDeleteModal.value = true
}

function showToast(message, type = 'success') {
  toast.message = message
  toast.type = type
  toast.show = true
  setTimeout(() => toast.show = false, 3000)
}

onMounted(() => fetchAreas())
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700;800&display=swap');
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.areas-page { display: flex; flex-direction: column; gap: 20px; font-family: 'DM Sans', sans-serif; }

/* ── Header ── */
.page-header { display: flex; align-items: center; gap: 12px; }
.search-wrap { position: relative; flex: 1; max-width: 380px; }
.search-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #94A3B8; pointer-events: none; }
.search-input {
  width: 100%; padding: 10px 36px 10px 38px;
  border: 1.5px solid #E2E8F0; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 14px; color: #374151;
  background: white; outline: none; transition: border-color 0.2s, box-shadow 0.2s;
}
.search-input:focus { border-color: #0EA5E9; box-shadow: 0 0 0 3px rgba(14,165,233,0.1); }
.search-input::placeholder { color: #CBD5E1; }
.search-clear { position: absolute; right: 11px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94A3B8; padding: 3px; display: flex; transition: color 0.2s; }
.search-clear:hover { color: #EF4444; }
.search-clear svg { width: 14px; height: 14px; }

.btn-primary {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 10px 18px; background: linear-gradient(135deg, #0369A1, #0EA5E9);
  color: white; border: none; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600;
  cursor: pointer; transition: all 0.2s; white-space: nowrap;
}
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(14,165,233,0.35); }
.btn-primary svg { width: 16px; height: 16px; }

/* ── Stats strip ── */
.stats-strip {
  background: white; border: 1px solid #E2E8F0; border-radius: 14px;
  padding: 16px 28px; display: flex; align-items: center;
}
.strip-stat { display: flex; flex-direction: column; align-items: center; flex: 1; }
.strip-num  { font-family: 'Syne', sans-serif; font-size: 24px; font-weight: 800; color: #0C4A6E; }
.strip-label { font-size: 11px; color: #94A3B8; font-weight: 500; margin-top: 2px; }
.strip-divider { width: 1px; height: 36px; background: #F1F5F9; }

/* ── Loading / Empty ── */
.loading-state {
  display: flex; align-items: center; justify-content: center; gap: 12px;
  padding: 60px; color: #94A3B8; font-size: 14px;
}
.loading-spinner {
  width: 22px; height: 22px;
  border: 2.5px solid #E2E8F0; border-top-color: #0EA5E9;
  border-radius: 50%; animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.empty-state {
  text-align: center; padding: 60px 20px;
  display: flex; flex-direction: column; align-items: center; gap: 12px;
}
.empty-state svg { width: 64px; height: 64px; }
.empty-title { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 800; color: #374151; }
.empty-sub { font-size: 13px; color: #94A3B8; margin-bottom: 4px; }

/* ── Areas grid ── */
.areas-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 18px;
}

.area-card {
  background: white; border-radius: 16px; border: 1px solid #E2E8F0;
  overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;
}
.area-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.08); }

.card-bar { height: 4px; }

.card-body { padding: 20px; }

.card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }

.area-icon {
  width: 44px; height: 44px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 800;
}

.card-actions { display: flex; gap: 5px; }
.icon-btn {
  width: 30px; height: 30px; border-radius: 7px; border: none;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: all 0.18s;
}
.icon-btn svg { width: 13px; height: 13px; }
.icon-btn.edit   { background: #F0F9FF; color: #0369A1; }
.icon-btn.edit:hover   { background: #BAE6FD; }
.icon-btn.delete { background: #FEF2F2; color: #DC2626; }
.icon-btn.delete:hover { background: #FECACA; }

.area-name { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 800; color: #0C4A6E; margin-bottom: 3px; }
.area-company { font-size: 12px; color: #94A3B8; margin-bottom: 14px; }

.area-metrics { display: flex; gap: 16px; margin-bottom: 14px; }
.metric { display: flex; align-items: center; gap: 5px; font-size: 12px; color: #64748B; }
.metric svg { width: 13px; height: 13px; color: #94A3B8; }

.area-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 12px; border-top: 1px solid #F8FAFC; }

.risk-chip {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 100px;
}
.risk-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
.r-bajo    { background: #F0FDF4; color: #16A34A; }
.r-medio   { background: #FFFBEB; color: #D97706; }
.r-alto    { background: #FEF2F2; color: #DC2626; }
.r-muyalto { background: #F5F3FF; color: #7C3AED; }
.r-none    { background: #F8FAFC; color: #94A3B8; }

.view-employees {
  font-size: 12px; color: #0EA5E9; font-weight: 600;
  text-decoration: none; transition: color 0.2s;
}
.view-employees:hover { color: #0284C7; }

/* ══ MODAL ══ */
.modal-overlay {
  position: fixed; inset: 0; z-index: 999;
  background: rgba(12,21,36,0.55); backdrop-filter: blur(4px);
  display: flex; align-items: center; justify-content: center; padding: 20px;
}
.modal-box {
  background: white; border-radius: 20px; width: 100%; max-width: 480px;
  box-shadow: 0 32px 80px rgba(0,0,0,0.2); overflow: hidden;
}
.modal-sm { max-width: 400px; padding: 36px; text-align: center; }

.modal-header {
  display: flex; align-items: flex-start; gap: 14px; padding: 24px 24px 0;
}
.modal-icon {
  width: 44px; height: 44px; background: #F0F9FF; border-radius: 12px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; color: #0369A1;
}
.modal-icon svg { width: 22px; height: 22px; }
.modal-title { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; color: #0C4A6E; }
.modal-sub { font-size: 13px; color: #94A3B8; margin-top: 2px; }
.modal-close {
  margin-left: auto; background: none; border: none; cursor: pointer;
  color: #94A3B8; padding: 4px; border-radius: 6px; transition: all 0.18s;
  display: flex;
}
.modal-close:hover { color: #374151; background: #F1F5F9; }
.modal-close svg { width: 18px; height: 18px; }

.modal-body { padding: 24px; display: flex; flex-direction: column; gap: 16px; }

.form-field { display: flex; flex-direction: column; gap: 6px; }
.form-label { font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.5px; }
.required { color: #EF4444; }
.form-input {
  padding: 11px 14px; border: 1.5px solid #E2E8F0; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 14px; color: #1E293B;
  background: #F8FAFC; outline: none; transition: border-color 0.2s, box-shadow 0.2s;
}
.form-input:focus { border-color: #0EA5E9; background: white; box-shadow: 0 0 0 3px rgba(14,165,233,0.1); }
.has-error .form-input { border-color: #F87171; }
.form-error { font-size: 12px; color: #EF4444; }

.preview-area {
  display: flex; align-items: center; gap: 12px;
  background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 12px 14px;
}
.preview-icon {
  width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800;
}
.preview-name { font-size: 14px; font-weight: 600; color: #1E293B; }
.preview-meta { font-size: 12px; color: #94A3B8; margin-top: 2px; }

.suggestions {}
.sug-label { font-size: 11px; color: #94A3B8; font-weight: 600; margin-bottom: 8px; }
.sug-chips { display: flex; flex-wrap: wrap; gap: 6px; }
.sug-chip {
  padding: 5px 12px; border-radius: 100px;
  border: 1.5px solid #E2E8F0; background: white;
  font-family: 'DM Sans', sans-serif; font-size: 12px; color: #475569; font-weight: 500;
  cursor: pointer; transition: all 0.18s;
}
.sug-chip:hover { border-color: #0EA5E9; color: #0369A1; background: #F0F9FF; }

.modal-footer {
  display: flex; justify-content: flex-end; gap: 10px;
  padding: 16px 24px; border-top: 1px solid #F1F5F9;
}
.modal-footer-center { justify-content: center; border: none; padding: 0; margin-top: 24px; }

.btn-ghost {
  padding: 10px 18px; background: white; color: #64748B;
  border: 1.5px solid #E2E8F0; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 500;
  cursor: pointer; transition: all 0.2s;
}
.btn-ghost:hover { border-color: #CBD5E1; color: #374151; }

.btn-danger {
  padding: 10px 18px; background: #EF4444; color: white;
  border: none; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600;
  cursor: pointer; transition: all 0.2s;
}
.btn-danger:hover { background: #DC2626; }

/* Delete modal */
.delete-icon { margin: 0 auto 16px; width: 64px; }
.delete-icon svg { width: 64px; height: 64px; }
.delete-title { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; color: #1E293B; margin-bottom: 8px; }
.delete-sub { font-size: 14px; color: #64748B; line-height: 1.6; }
.delete-warn { display: block; color: #D97706; font-size: 13px; margin-top: 8px; font-weight: 500; }

/* Toast */
.toast {
  position: fixed; bottom: 28px; right: 28px; z-index: 9999;
  display: flex; align-items: center; gap: 10px;
  padding: 14px 20px; border-radius: 12px;
  font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 500;
  box-shadow: 0 8px 24px rgba(0,0,0,0.15); min-width: 260px;
}
.toast svg { width: 18px; height: 18px; flex-shrink: 0; }
.toast.success { background: #0C4A6E; color: white; }
.toast.error   { background: #DC2626; color: white; }

/* Dots */
.dots { display: inline-flex; gap: 4px; align-items: center; }
.dots span { width: 5px; height: 5px; background: white; border-radius: 50%; animation: bounce 1s ease-in-out infinite; }
.dots span:nth-child(2) { animation-delay: 0.15s; }
.dots span:nth-child(3) { animation-delay: 0.3s; }
@keyframes bounce { 0%,80%,100%{transform:scale(0.8);opacity:.5} 40%{transform:scale(1.1);opacity:1} }

/* Transitions */
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .modal-box, .modal-leave-to .modal-box { transform: scale(0.95) translateY(10px); }
.toast-enter-active, .toast-leave-active { transition: all 0.3s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(12px); }

/* Responsive */
@media (max-width: 640px) {
  .areas-grid { grid-template-columns: 1fr; }
  .stats-strip { flex-wrap: wrap; gap: 16px; }
  .page-header { flex-wrap: wrap; }
}
</style>

