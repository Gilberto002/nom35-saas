<template>
  <MainLayout current-page="/employees" page-title="Empleados">
    <div class="employees-page">

      <!-- Header actions -->
      <div class="page-actions">
        <div class="search-wrap">
          <svg class="search-icon" viewBox="0 0 20 20" fill="none">
            <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.4"/>
            <path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
          </svg>
          <input v-model="searchQuery" type="text" placeholder="Buscar por nombre, email o área..." class="search-input" />
          <button v-if="searchQuery" class="search-clear" @click="searchQuery = ''">
            <svg viewBox="0 0 16 16" fill="none"><path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
          </button>
        </div>
        <div class="actions-right">
          <div class="filter-wrap">
            <svg viewBox="0 0 20 20" fill="none" class="filter-icon"><path d="M3 5h14M6 10h8M9 15h2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
            <select v-model="filterArea" class="filter-select">
              <option value="">Todas las áreas</option>
              <option v-for="a in areas" :key="a.id" :value="a.id">{{ a.name }}</option>
            </select>
          </div>
          <button class="btn-primary" @click="openModal()">
            <svg viewBox="0 0 20 20" fill="none"><path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Nuevo Empleado
          </button>
        </div>
      </div>

      <!-- Stats strip -->
      <div class="stats-strip">
        <div class="strip-stat">
          <span class="strip-num">{{ employees.length }}</span>
          <span class="strip-label">Total empleados</span>
        </div>
        <div class="strip-divider"></div>
        <div class="strip-stat">
          <span class="strip-num">{{ areas.length }}</span>
          <span class="strip-label">Áreas registradas</span>
        </div>
        <div class="strip-divider"></div>
        <div class="strip-stat">
          <span class="strip-num">{{ employeesWithEval }}</span>
          <span class="strip-label">Con evaluación</span>
        </div>
        <div class="strip-divider"></div>
        <div class="strip-stat">
          <span class="strip-num">{{ employeesPending }}</span>
          <span class="strip-label">Pendientes</span>
        </div>
      </div>

      <!-- Table card -->
      <div class="table-card">
        <div class="table-wrap">
          <table class="emp-table">
            <thead>
              <tr>
                <th class="th-check">
                  <label class="checkbox-wrap">
                    <input type="checkbox" v-model="allSelected" @change="toggleAll" />
                    <span class="checkbox-custom"></span>
                  </label>
                </th>
                <th @click="sort('first_name')" class="sortable">
                  Empleado
                  <svg viewBox="0 0 16 16" fill="none"><path d="M5 6l3-3 3 3M5 10l3 3 3-3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                </th>
                <th @click="sort('email')" class="sortable">
                  Email
                  <svg viewBox="0 0 16 16" fill="none"><path d="M5 6l3-3 3 3M5 10l3 3 3-3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                </th>
                <th @click="sort('area')" class="sortable">Área</th>
                <th>Evaluaciones</th>
                <th>Estado</th>
                <th class="th-actions">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="isLoading">
                <td colspan="7" class="state-row">
                  <div class="loading-spinner"></div>
                  <span>Cargando empleados...</span>
                </td>
              </tr>
              <tr v-else-if="paginatedEmployees.length === 0">
                <td colspan="7" class="state-row empty">
                  <div class="empty-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                      <circle cx="24" cy="18" r="8" stroke="#C4C4C0" stroke-width="2"/>
                      <path d="M8 40c0-8.8 7.2-16 16-16s16 7.2 16 16" stroke="#C4C4C0" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                  </div>
                  <p class="empty-title">No hay empleados</p>
                  <p class="empty-sub">{{ searchQuery ? 'Intenta con otra búsqueda' : 'Agrega tu primer empleado' }}</p>
                  <button v-if="!searchQuery" class="btn-primary btn-sm" @click="openModal()">+ Agregar empleado</button>
                </td>
              </tr>
              <tr v-else v-for="emp in paginatedEmployees" :key="emp.id"
                class="emp-row" :class="{ selected: selectedIds.includes(emp.id) }">
                <td class="td-check">
                  <label class="checkbox-wrap">
                    <input type="checkbox" :value="emp.id" v-model="selectedIds" />
                    <span class="checkbox-custom"></span>
                  </label>
                </td>
                <td>
                  <div class="emp-cell">
                    <div class="emp-avatar" :style="{ background: avatarGradient(emp.first_name) }">
                      {{ initials(emp.first_name, emp.last_name) }}
                    </div>
                    <div class="emp-info">
                      <span class="emp-name">{{ emp.first_name }} {{ emp.last_name }}</span>
                      <span class="emp-id">#{{ String(emp.id).padStart(4,'0') }}</span>
                    </div>
                  </div>
                </td>
                <td class="td-email">{{ emp.email }}</td>
                <td>
                  <span class="area-chip">{{ getAreaName(emp.area_id) }}</span>
                </td>
                <td>
                  <div class="eval-cell">
                    <span class="eval-count">{{ emp.evaluations_count || 0 }}</span>
                    <div class="eval-dots">
                      <span v-for="n in 3" :key="n" class="eval-dot" :class="{ filled: n <= (emp.evaluations_count || 0) }"></span>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="status-badge" :class="emp.evaluations_count > 0 ? 'status-done' : 'status-pending'">
                    <span class="status-dot"></span>
                    {{ emp.evaluations_count > 0 ? 'Evaluado' : 'Pendiente' }}
                  </span>
                </td>
                <td class="td-actions">
                  <div class="action-group">
                    <button class="action-btn eval-btn" @click="startEvaluation(emp)" title="Iniciar evaluación">
                      <svg viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <button class="action-btn edit-btn" @click="openModal(emp)" title="Editar">
                      <svg viewBox="0 0 16 16" fill="none"><path d="M11 2l3 3-8 8H3v-3l8-8z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                    </button>
                    <button class="action-btn delete-btn" @click="confirmDelete(emp)" title="Eliminar">
                      <svg viewBox="0 0 16 16" fill="none"><path d="M3 4h10M6 4V3h4v1M5 4v8a1 1 0 001 1h4a1 1 0 001-1V4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Bulk actions + pagination -->
        <div class="table-footer">
          <div class="bulk-actions" v-if="selectedIds.length > 0">
            <span class="bulk-count">{{ selectedIds.length }} seleccionados</span>
            <button class="bulk-btn danger" @click="bulkDelete">
              <svg viewBox="0 0 16 16" fill="none"><path d="M3 4h10M6 4V3h4v1M5 4v8a1 1 0 001 1h4a1 1 0 001-1V4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
              Eliminar seleccionados
            </button>
          </div>
          <div class="pagination" :class="{ 'ml-auto': selectedIds.length === 0 }">
            <span class="page-info">
              {{ (currentPage - 1) * pageSize + 1 }}–{{ Math.min(currentPage * pageSize, filteredEmployees.length) }}
              de {{ filteredEmployees.length }}
            </span>
            <div class="page-controls">
              <button class="page-btn" :disabled="currentPage === 1" @click="currentPage--">
                <svg viewBox="0 0 16 16" fill="none"><path d="M10 4L6 8l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              </button>
              <button v-for="p in totalPages" :key="p"
                class="page-num" :class="{ active: p === currentPage }"
                @click="currentPage = p">{{ p }}</button>
              <button class="page-btn" :disabled="currentPage === totalPages" @click="currentPage++">
                <svg viewBox="0 0 16 16" fill="none"><path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal: Crear / Editar -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showModal" class="modal-overlay" @mousedown.self="closeModal">
          <div class="modal-box">
            <div class="modal-header">
              <div class="modal-icon">
                <svg viewBox="0 0 24 24" fill="none">
                  <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.5"/>
                  <path d="M4 20c0-4.4 3.6-8 8-8s8 3.6 8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
              </div>
              <div>
                <h2 class="modal-title">{{ editMode ? 'Editar Empleado' : 'Nuevo Empleado' }}</h2>
                <p class="modal-sub">{{ editMode ? 'Actualiza los datos del empleado' : 'Completa la información del nuevo empleado' }}</p>
              </div>
              <button class="modal-close" @click="closeModal">
                <svg viewBox="0 0 20 20" fill="none"><path d="M5 5l10 10M15 5L5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-row">
                <div class="form-field" :class="{ 'has-error': formErrors.first_name }">
                  <label class="form-label">Nombre <span class="required">*</span></label>
                  <input v-model="form.first_name" type="text" placeholder="Ej. María" class="form-input" />
                  <span v-if="formErrors.first_name" class="form-error">{{ formErrors.first_name }}</span>
                </div>
                <div class="form-field" :class="{ 'has-error': formErrors.last_name }">
                  <label class="form-label">Apellido <span class="required">*</span></label>
                  <input v-model="form.last_name" type="text" placeholder="Ej. González" class="form-input" />
                  <span v-if="formErrors.last_name" class="form-error">{{ formErrors.last_name }}</span>
                </div>
              </div>
              <div class="form-field" :class="{ 'has-error': formErrors.email }">
                <label class="form-label">Correo electrónico <span class="required">*</span></label>
                <input v-model="form.email" type="email" placeholder="correo@empresa.com" class="form-input" />
                <span v-if="formErrors.email" class="form-error">{{ formErrors.email }}</span>
              </div>
              <div class="form-field" :class="{ 'has-error': formErrors.area_id }">
                <label class="form-label">Área <span class="required">*</span></label>
                <select v-model="form.area_id" class="form-select">
                  <option value="">Selecciona un área</option>
                  <option v-for="a in areas" :key="a.id" :value="a.id">{{ a.name }}</option>
                </select>
                <span v-if="formErrors.area_id" class="form-error">{{ formErrors.area_id }}</span>
              </div>
              <div v-if="form.first_name || form.last_name" class="preview-card">
                <div class="preview-avatar" :style="{ background: avatarGradient(form.first_name) }">
                  {{ initials(form.first_name, form.last_name) }}
                </div>
                <div>
                  <p class="preview-name">{{ form.first_name }} {{ form.last_name }}</p>
                  <p class="preview-meta">{{ form.email || 'Sin correo' }} · {{ getAreaName(form.area_id) || 'Sin área' }}</p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn-ghost" @click="closeModal" :disabled="isSaving">Cancelar</button>
              <button class="btn-primary" @click="saveEmployee" :disabled="isSaving">
                <span v-if="!isSaving">{{ editMode ? 'Guardar cambios' : 'Crear empleado' }}</span>
                <span v-else class="saving-dots"><span></span><span></span><span></span></span>
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Modal: Confirmar eliminación -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showDeleteModal" class="modal-overlay" @mousedown.self="showDeleteModal = false">
          <div class="modal-box modal-sm">
            <div class="delete-icon">
              <svg viewBox="0 0 48 48" fill="none">
                <circle cx="24" cy="24" r="20" fill="#FEF2F2" stroke="#FECACA" stroke-width="2"/>
                <path d="M16 16l16 16M32 16L16 32" stroke="#E8180C" stroke-width="2.5" stroke-linecap="round"/>
              </svg>
            </div>
            <h3 class="delete-title">¿Eliminar empleado?</h3>
            <p class="delete-sub">
              Se eliminará a <strong>{{ deleteTarget?.first_name }} {{ deleteTarget?.last_name }}</strong> y todos sus registros.
              Esta acción no se puede deshacer.
            </p>
            <div class="modal-footer">
              <button class="btn-ghost" @click="showDeleteModal = false">Cancelar</button>
              <button class="btn-danger" @click="deleteEmployee" :disabled="isSaving">
                <span v-if="!isSaving">Sí, eliminar</span>
                <span v-else class="saving-dots"><span></span><span></span><span></span></span>
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
import { ref, reactive, computed, onMounted } from 'vue'
import MainLayout from '../Layouts/MainLayout.vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

const isLoading = ref(false)
const isSaving = ref(false)
const searchQuery = ref('')
const filterArea = ref('')
const currentPage = ref(1)
const pageSize = 10
const sortKey = ref('first_name')
const sortDir = ref('asc')
const selectedIds = ref([])
const allSelected = ref(false)
const showModal = ref(false)
const showDeleteModal = ref(false)
const editMode = ref(false)
const deleteTarget = ref(null)

const toast = reactive({ show: false, message: '', type: 'success' })
const form = reactive({ first_name: '', last_name: '', email: '', area_id: '' })
const formErrors = reactive({ first_name: '', last_name: '', email: '', area_id: '' })
let editingId = null

const employees = ref([])
const areas = ref([])

const employeesWithEval = computed(() => employees.value.filter(e => e.evaluations_count > 0).length)
const employeesPending = computed(() => employees.value.filter(e => !e.evaluations_count).length)

const filteredEmployees = computed(() => {
  let data = [...employees.value]
  if (filterArea.value) data = data.filter(e => e.area_id == filterArea.value)
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    data = data.filter(e =>
      `${e.first_name} ${e.last_name}`.toLowerCase().includes(q) ||
      e.email.toLowerCase().includes(q) ||
      getAreaName(e.area_id).toLowerCase().includes(q)
    )
  }
  data.sort((a, b) => {
    const av = a[sortKey.value] || '', bv = b[sortKey.value] || ''
    return sortDir.value === 'asc' ? av.localeCompare(bv) : bv.localeCompare(av)
  })
  return data
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredEmployees.value.length / pageSize)))
const paginatedEmployees = computed(() => {
  const start = (currentPage.value - 1) * pageSize
  return filteredEmployees.value.slice(start, start + pageSize)
})

async function fetchEmployees() {
  isLoading.value = true
  try {
    const { data } = await axios.get('/api/employees')
    employees.value = data.data || data
  } catch (e) {
    showToast('Error al cargar empleados', 'error')
  } finally {
    isLoading.value = false
  }
}

async function fetchAreas() {
  try {
    const { data } = await axios.get('/api/areas')
    areas.value = data.data || data
  } catch (e) {
    console.error('Error cargando áreas', e)
  }
}

async function saveEmployee() {
  if (!validateForm()) return
  isSaving.value = true
  try {
    if (editMode.value) {
      await axios.put(`/api/employees/${editingId}`, form)
      showToast('Empleado actualizado correctamente')
    } else {
      await axios.post('/api/employees', form)
      showToast('Empleado creado correctamente')
    }
    closeModal()
    await fetchEmployees()
  } catch (e) {
    if (e.response?.status === 422) {
      const errs = e.response.data.errors || {}
      Object.keys(errs).forEach(k => { if (formErrors[k] !== undefined) formErrors[k] = errs[k][0] })
    } else {
      showToast('Error al guardar el empleado', 'error')
    }
  } finally {
    isSaving.value = false
  }
}

async function deleteEmployee() {
  if (!deleteTarget.value) return
  isSaving.value = true
  try {
    await axios.delete(`/api/employees/${deleteTarget.value.id}`)
    showToast('Empleado eliminado')
    showDeleteModal.value = false
    await fetchEmployees()
  } catch (e) {
    showToast('Error al eliminar', 'error')
  } finally {
    isSaving.value = false
  }
}

async function bulkDelete() {
  if (!confirm(`¿Eliminar ${selectedIds.value.length} empleados?`)) return
  try {
    await Promise.all(selectedIds.value.map(id => axios.delete(`/api/employees/${id}`)))
    selectedIds.value = []
    showToast('Empleados eliminados')
    await fetchEmployees()
  } catch (e) {
    showToast('Error en eliminación masiva', 'error')
  }
}

function openModal(emp = null) {
  clearForm()
  if (emp) {
    editMode.value = true
    editingId = emp.id
    form.first_name = emp.first_name
    form.last_name = emp.last_name
    form.email = emp.email
    form.area_id = emp.area_id
  } else {
    editMode.value = false
    editingId = null
  }
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  clearForm()
}

function clearForm() {
  form.first_name = ''
  form.last_name = ''
  form.email = ''
  form.area_id = ''
  Object.keys(formErrors).forEach(k => formErrors[k] = '')
}

function confirmDelete(emp) {
  deleteTarget.value = emp
  showDeleteModal.value = true
}

function validateForm() {
  let valid = true
  Object.keys(formErrors).forEach(k => formErrors[k] = '')
  if (!form.first_name.trim()) { formErrors.first_name = 'El nombre es requerido'; valid = false }
  if (!form.last_name.trim()) { formErrors.last_name = 'El apellido es requerido'; valid = false }
  if (!form.email.trim()) { formErrors.email = 'El correo es requerido'; valid = false }
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) { formErrors.email = 'Correo inválido'; valid = false }
  if (!form.area_id) { formErrors.area_id = 'Selecciona un área'; valid = false }
  return valid
}

function getAreaName(areaId) {
  return areas.value.find(a => a.id == areaId)?.name || '—'
}

function sort(key) {
  if (sortKey.value === key) sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc'
  else { sortKey.value = key; sortDir.value = 'asc' }
}

function toggleAll() {
  selectedIds.value = allSelected.value ? paginatedEmployees.value.map(e => e.id) : []
}

function startEvaluation(emp) {
  router.visit(`/evaluations/start?employee_id=${emp.id}`)
}

function initials(first, last) {
  return `${first?.[0] || ''}${last?.[0] || ''}`.toUpperCase()
}

const gradients = [
  'linear-gradient(135deg,#0A0A0A,#E8180C)',
  'linear-gradient(135deg,#E8180C,#F59E0B)',
  'linear-gradient(135deg,#374151,#6B7280)',
  'linear-gradient(135deg,#0A0A0A,#374151)',
  'linear-gradient(135deg,#E8180C,#0A0A0A)',
  'linear-gradient(135deg,#7A7A72,#0A0A0A)',
]
function avatarGradient(name) {
  return gradients[(name?.charCodeAt(0) || 0) % gradients.length]
}

function showToast(message, type = 'success') {
  toast.message = message
  toast.type = type
  toast.show = true
  setTimeout(() => toast.show = false, 3000)
}

onMounted(() => {
  fetchEmployees()
  fetchAreas()
})
</script>


<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600;700&display=swap');
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --black:   #0A0A0A;
  --red:     #E8180C;
  --bg:      #F2F2F0;
  --white:   #ffffff;
  --muted:   #7A7A72;
  --border:  #E4E4E0;
  --radius:  12px;
  --shadow:  0 1px 3px rgba(0,0,0,0.06), 0 4px 12px rgba(0,0,0,0.06);
  --shadow-lg: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
}

.employees-page { display: flex; flex-direction: column; gap: 20px; font-family: 'DM Sans', sans-serif; }

/* ══ Page actions ══ */
.page-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

.search-wrap { position: relative; flex: 1; min-width: 260px; }
.search-icon {
  position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
  width: 15px; height: 15px; color: var(--muted); pointer-events: none;
}
.search-input {
  width: 100%; padding: 10px 36px 10px 38px;
  border: 1.5px solid var(--border); border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--black);
  background: var(--white); outline: none;
  box-shadow: var(--shadow);
  transition: border-color 0.2s, box-shadow 0.2s;
}
.search-input:focus {
  border-color: #C4C4C0;
  box-shadow: 0 0 0 3px rgba(0,0,0,0.05), var(--shadow);
}
.search-input::placeholder { color: #C4C4C0; }
.search-clear {
  position: absolute; right: 11px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer;
  color: var(--muted); padding: 3px; display: flex; align-items: center; transition: color 0.2s;
}
.search-clear:hover { color: var(--red); }
.search-clear svg { width: 13px; height: 13px; }

.actions-right { display: flex; align-items: center; gap: 10px; }
.filter-wrap { position: relative; display: flex; align-items: center; }
.filter-icon { position: absolute; left: 10px; width: 14px; height: 14px; color: var(--muted); pointer-events: none; }
.filter-select {
  padding: 9px 32px 9px 30px;
  border: 1.5px solid var(--border); border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--black);
  background: var(--white) url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M4 6l4 4 4-4' stroke='%237A7A72' stroke-width='1.4' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 8px center;
  appearance: none; cursor: pointer; outline: none;
  box-shadow: var(--shadow);
  transition: border-color 0.2s;
}
.filter-select:focus { border-color: var(--black); }

/* ══ Buttons ══ */
.btn-primary {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 10px 20px;
  background: var(--black); color: var(--white);
  border: 2px solid var(--black); border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; white-space: nowrap; letter-spacing: 0.3px;
  box-shadow: 0 2px 8px rgba(10,10,10,0.2);
  transition: background 0.18s, border-color 0.18s, box-shadow 0.18s, transform 0.15s;
}
.btn-primary:hover {
  background: var(--red); border-color: var(--red);
  box-shadow: 0 4px 16px rgba(232,24,12,0.3);
  transform: translateY(-1px);
}
.btn-primary:active { transform: translateY(0); }
.btn-primary svg { width: 15px; height: 15px; }
.btn-primary.btn-sm { padding: 8px 14px; font-size: 12px; }

.btn-ghost {
  padding: 10px 20px;
  background: var(--white); color: var(--muted);
  border: 1.5px solid var(--border); border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: all 0.18s;
  box-shadow: var(--shadow);
}
.btn-ghost:hover { border-color: var(--black); color: var(--black); }

.btn-danger {
  padding: 10px 20px;
  background: var(--red); color: var(--white);
  border: 2px solid var(--red); border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: all 0.18s;
  box-shadow: 0 2px 8px rgba(232,24,12,0.25);
}
.btn-danger:hover { background: #c4140a; border-color: #c4140a; transform: translateY(-1px); }

/* ══ Stats strip → individual cards ══ */
.stats-strip {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
  background: transparent;
  border: none;
  padding: 0;
  box-shadow: none;
}
.stats-strip::before, .stats-strip::after { display: none; }

.strip-divider { display: none; }

.strip-stat {
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  padding: 18px 20px;
  display: flex; flex-direction: column; align-items: flex-start;
  position: relative; overflow: hidden;
  box-shadow: var(--shadow);
  transition: transform 0.18s, box-shadow 0.18s;
}
.strip-stat:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }

/* colored top bar per stat */
.strip-stat:nth-child(1)::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--black); border-radius: var(--radius) var(--radius) 0 0; }
.strip-stat:nth-child(3)::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: #22C55E; border-radius: var(--radius) var(--radius) 0 0; }
.strip-stat:nth-child(5)::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--red); border-radius: var(--radius) var(--radius) 0 0; }
.strip-stat:nth-child(7)::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: #F59E0B; border-radius: var(--radius) var(--radius) 0 0; }

.strip-num {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 40px; line-height: 1; color: var(--black); letter-spacing: -1px;
  margin-bottom: 4px;
}
.strip-label {
  font-size: 10px; font-weight: 700; letter-spacing: 1px;
  text-transform: uppercase; color: var(--muted);
}

/* ══ Table card ══ */
.table-card {
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow);
}
.table-wrap { overflow-x: auto; }

.emp-table { width: 100%; border-collapse: collapse; }
.emp-table thead tr { background: var(--bg); }
.emp-table th {
  padding: 12px 16px; text-align: left;
  font-size: 10px; font-weight: 700; color: var(--muted);
  text-transform: uppercase; letter-spacing: 1.2px;
  border-bottom: 1.5px solid var(--border);
  white-space: nowrap;
}
.emp-table th.sortable { cursor: pointer; user-select: none; transition: color 0.15s; }
.emp-table th.sortable:hover { color: var(--black); }
.emp-table th svg { width: 11px; height: 11px; margin-left: 4px; vertical-align: middle; color: var(--border); }
.th-check, .td-check { width: 44px; padding-left: 20px !important; }
.th-actions { text-align: right; padding-right: 20px !important; }
.td-actions { text-align: right; padding-right: 16px !important; }

.emp-row { border-bottom: 1px solid var(--bg); transition: background 0.12s; }
.emp-row:last-child { border-bottom: none; }
.emp-row:hover { background: #F7F7F5; }
.emp-row.selected { background: #F5F5F3; }
.emp-table td { padding: 14px 16px; font-size: 13px; color: var(--muted); vertical-align: middle; }

/* Checkbox */
.checkbox-wrap { display: inline-flex; align-items: center; cursor: pointer; }
.checkbox-wrap input { display: none; }
.checkbox-custom {
  width: 16px; height: 16px;
  border: 1.5px solid var(--border); border-radius: 4px; background: var(--white);
  transition: all 0.15s; position: relative; flex-shrink: 0;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.checkbox-wrap input:checked + .checkbox-custom { background: var(--black); border-color: var(--black); }
.checkbox-wrap input:checked + .checkbox-custom::after {
  content: '';
  position: absolute; left: 4px; top: 1px;
  width: 5px; height: 9px;
  border: 2px solid white; border-top: none; border-left: none;
  transform: rotate(45deg);
}

/* Employee cell */
.emp-cell { display: flex; align-items: center; gap: 12px; }
.emp-avatar {
  width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 700; color: white;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.emp-name { display: block; font-size: 13px; font-weight: 700; color: var(--black); }
.emp-id {
  display: block; font-size: 10px; color: #C4C4C0;
  font-weight: 600; letter-spacing: 0.5px; margin-top: 1px;
}
.td-email { color: var(--muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 12px; }

.area-chip {
  background: var(--bg); color: var(--black);
  font-size: 10px; font-weight: 700;
  padding: 3px 10px; border-radius: 100px;
  border: 1.5px solid var(--border);
  white-space: nowrap; letter-spacing: 0.5px; text-transform: uppercase;
}

/* Eval dots */
.eval-cell { display: flex; align-items: center; gap: 8px; }
.eval-count { font-family: 'Bebas Neue', sans-serif; font-size: 22px; color: var(--black); line-height: 1; }
.eval-dots { display: flex; gap: 3px; }
.eval-dot { width: 7px; height: 7px; border-radius: 2px; background: var(--border); transition: background 0.2s; }
.eval-dot.filled { background: var(--black); }

/* Status badge */
.status-badge {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 10px; font-weight: 700; letter-spacing: 0.8px;
  padding: 4px 10px; border-radius: 100px; white-space: nowrap; text-transform: uppercase;
}
.status-done    { background: #DCFCE7; color: #15803D; }
.status-pending { background: #FEF9C3; color: #B45309; }
.status-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

/* Action buttons */
.action-group { display: flex; align-items: center; justify-content: flex-end; gap: 5px; }
.action-btn {
  width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: all 0.15s;
  background: var(--white);
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.action-btn svg { width: 13px; height: 13px; }
.eval-btn  { color: var(--black); }
.eval-btn:hover  { background: var(--black); color: var(--white); border-color: var(--black); box-shadow: 0 2px 8px rgba(10,10,10,0.2); }
.edit-btn  { color: var(--black); }
.edit-btn:hover  { background: var(--black); color: var(--white); border-color: var(--black); box-shadow: 0 2px 8px rgba(10,10,10,0.2); }
.delete-btn { color: var(--muted); }
.delete-btn:hover { background: var(--red); color: var(--white); border-color: var(--red); box-shadow: 0 2px 8px rgba(232,24,12,0.25); }

/* ══ State rows ══ */
.state-row { text-align: center; padding: 56px 16px !important; color: var(--muted); font-size: 13px; font-weight: 600; }
.loading-spinner {
  width: 20px; height: 20px;
  border: 2px solid var(--border); border-top-color: var(--black);
  border-radius: 50%; animation: spin 0.7s linear infinite; display: inline-block;
  margin-bottom: 10px;
}
@keyframes spin { to { transform: rotate(360deg); } }
.empty-icon { margin: 0 auto 14px; width: 52px; }
.empty-icon svg { width: 52px; height: 52px; }
.empty-title { font-family: 'Bebas Neue', sans-serif; font-size: 22px; letter-spacing: 1px; color: var(--black); margin-bottom: 5px; }
.empty-sub { font-size: 12px; color: var(--muted); margin-bottom: 18px; }

/* ══ Table footer ══ */
.table-footer {
  display: flex; align-items: center;
  padding: 14px 20px; border-top: 1.5px solid var(--border); gap: 16px;
  background: var(--bg);
}
.bulk-actions { display: flex; align-items: center; gap: 10px; }
.bulk-count { font-size: 12px; font-weight: 700; color: var(--black); }
.bulk-btn {
  display: flex; align-items: center; gap: 5px;
  padding: 6px 13px; border-radius: 7px; border: none;
  font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 700; cursor: pointer;
  transition: all 0.15s;
}
.bulk-btn.danger { background: #FEE2E2; color: var(--red); }
.bulk-btn.danger:hover { background: var(--red); color: var(--white); }
.bulk-btn svg { width: 12px; height: 12px; }

.pagination { display: flex; align-items: center; gap: 8px; margin-left: auto; }
.ml-auto { margin-left: auto; }
.page-info { font-size: 11px; color: var(--muted); font-weight: 600; margin-right: 4px; }
.page-controls { display: flex; gap: 3px; }
.page-btn {
  width: 30px; height: 30px; border: 1.5px solid var(--border); background: var(--white);
  border-radius: 7px; cursor: pointer; color: var(--muted);
  display: flex; align-items: center; justify-content: center; transition: all 0.15s;
  box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}
.page-btn:hover:not(:disabled) { border-color: var(--black); color: var(--black); }
.page-btn:disabled { opacity: 0.35; cursor: not-allowed; }
.page-btn svg { width: 12px; height: 12px; }
.page-num {
  width: 30px; height: 30px; border: 1.5px solid var(--border); background: var(--white);
  border-radius: 7px; cursor: pointer; color: var(--muted); font-size: 12px; font-weight: 700;
  transition: all 0.15s; box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}
.page-num.active { background: var(--black); border-color: var(--black); color: var(--white); box-shadow: 0 2px 8px rgba(10,10,10,0.2); }
.page-num:hover:not(.active) { border-color: var(--black); color: var(--black); }

/* ══ Modal overlay ══ */
.modal-overlay {
  position: fixed; inset: 0; z-index: 999;
  background: rgba(10,10,10,0.55);
  backdrop-filter: blur(6px);
  display: flex; align-items: center; justify-content: center; padding: 20px;
}

/* ══ Modal box ══ */
.modal-box {
  background: var(--white);
  border-radius: 18px;
  width: 100%; max-width: 520px;
  box-shadow: 0 40px 100px rgba(0,0,0,0.3), 0 8px 24px rgba(0,0,0,0.1);
  overflow: hidden;
  border: 1.5px solid var(--border);
}
.modal-sm { max-width: 400px; padding: 36px; text-align: center; border-radius: 18px; }

/* Header light — legible y profesional */
.modal-header {
  display: flex; align-items: center; gap: 14px;
  padding: 20px 24px;
  background: var(--white);
  border-bottom: 1.5px solid var(--border);
  position: relative;
}
.modal-header::before {
  content: ''; position: absolute;
  top: 0; left: 0; right: 0; height: 3px;
  background: var(--red);
  border-radius: 18px 18px 0 0;
}
.modal-icon {
  width: 40px; height: 40px;
  background: var(--bg);
  border: 1.5px solid var(--border);
  border-radius: 10px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  color: var(--black);
}
.modal-icon svg { width: 19px; height: 19px; }
.modal-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 20px; letter-spacing: 1.5px;
  color: var(--black); line-height: 1;
}
.modal-sub { font-size: 11px; color: var(--muted); margin-top: 3px; font-weight: 500; }
.modal-close {
  margin-left: auto; background: var(--bg); border: 1.5px solid var(--border);
  cursor: pointer; color: var(--muted);
  padding: 6px; border-radius: 7px; transition: all 0.15s;
  display: flex; align-items: center;
}
.modal-close:hover { color: var(--red); background: #FEE2E2; border-color: #FECACA; }
.modal-close svg { width: 15px; height: 15px; }

.modal-body { padding: 22px 24px; display: flex; flex-direction: column; gap: 16px; background: var(--white); }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-field { display: flex; flex-direction: column; gap: 6px; }
.form-label {
  font-size: 10px; font-weight: 700; color: var(--black);
  text-transform: uppercase; letter-spacing: 1.2px;
}
.required { color: var(--red); }
.form-input, .form-select {
  padding: 10px 13px;
  border: 1.5px solid var(--border); border-radius: 9px;
  font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--black);
  background: var(--bg); outline: none;
  box-shadow: inset 0 1px 3px rgba(0,0,0,0.04);
  transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}
.form-input::placeholder { color: #C4C4C0; }
.form-input:focus, .form-select:focus {
  border-color: var(--black); background: var(--white);
  box-shadow: 0 0 0 3px rgba(10,10,10,0.07);
}
.form-select {
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M4 6l4 4 4-4' stroke='%237A7A72' stroke-width='1.4' stroke-linecap='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 10px center;
  background-color: var(--bg); padding-right: 32px; cursor: pointer;
}
.form-select:focus { background-color: var(--white); }
.has-error .form-input, .has-error .form-select {
  border-color: var(--red);
  box-shadow: 0 0 0 3px rgba(232,24,12,0.08);
}
.form-error { font-size: 11px; color: var(--red); font-weight: 600; }

.preview-card {
  display: flex; align-items: center; gap: 13px;
  background: var(--bg); border: 1.5px solid var(--border); border-radius: 10px;
  padding: 13px 16px;
  box-shadow: inset 0 1px 3px rgba(0,0,0,0.03);
}
.preview-avatar {
  width: 40px; height: 40px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 700; color: white; flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.preview-name { font-size: 14px; font-weight: 700; color: var(--black); }
.preview-meta { font-size: 11px; color: var(--muted); margin-top: 2px; }

.modal-footer {
  display: flex; justify-content: flex-end; gap: 10px;
  padding: 16px 24px; border-top: 1.5px solid var(--border);
  background: var(--bg);
}
.modal-sm .modal-footer { justify-content: center; padding: 0; margin-top: 24px; border: none; background: none; }

/* ══ Delete modal ══ */
.delete-icon { margin: 0 auto 18px; width: 60px; }
.delete-icon svg { width: 60px; height: 60px; }
.delete-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 24px; letter-spacing: 1px; color: var(--black); margin-bottom: 10px;
}
.delete-sub { font-size: 13px; color: var(--muted); line-height: 1.65; }
.delete-sub strong { color: var(--black); }

/* Saving dots */
.saving-dots { display: inline-flex; gap: 4px; align-items: center; height: 16px; }
.saving-dots span {
  width: 5px; height: 5px; background: white; border-radius: 50%;
  animation: bounce 1s ease-in-out infinite;
}
.saving-dots span:nth-child(2) { animation-delay: 0.15s; }
.saving-dots span:nth-child(3) { animation-delay: 0.3s; }
@keyframes bounce { 0%,80%,100% { transform: scale(0.7); opacity: 0.4; } 40% { transform: scale(1.1); opacity: 1; } }

/* ══ Toast ══ */
.toast {
  position: fixed; bottom: 28px; right: 28px; z-index: 9999;
  display: flex; align-items: center; gap: 10px;
  padding: 14px 20px; border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600;
  min-width: 260px; letter-spacing: 0.2px;
  box-shadow: 0 12px 32px rgba(0,0,0,0.25), 0 2px 8px rgba(0,0,0,0.1);
}
.toast svg { width: 16px; height: 16px; flex-shrink: 0; }
.toast.success { background: var(--black); color: var(--white); }
.toast.error   { background: var(--red);   color: var(--white); }

/* ══ Transitions ══ */
.modal-enter-active { transition: opacity 0.22s ease; }
.modal-leave-active { transition: opacity 0.18s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-active .modal-box { animation: modal-pop 0.28s cubic-bezier(0.22,1,0.36,1); }
.modal-leave-active .modal-box  { animation: modal-pop 0.18s cubic-bezier(0.22,1,0.36,1) reverse; }
@keyframes modal-pop {
  from { transform: scale(0.94) translateY(12px); opacity: 0; }
  to   { transform: scale(1) translateY(0); opacity: 1; }
}
.toast-enter-active, .toast-leave-active { transition: all 0.28s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(10px); }

/* ══ Responsive ══ */
@media (max-width: 640px) {
  .page-actions { flex-direction: column; align-items: stretch; }
  .actions-right { flex-wrap: wrap; }
  .form-row { grid-template-columns: 1fr; }
  .stats-strip { grid-template-columns: repeat(2, 1fr); }
}
</style>