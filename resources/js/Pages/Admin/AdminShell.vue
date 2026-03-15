<template>
  <div class="admin-shell">

    <aside class="admin-sidebar">
      <div class="admin-sidebar-header">
        <div class="admin-logo">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
            <rect width="28" height="28" rx="8" fill="#6366F1"/>
            <path d="M7 14L13 20L21 8" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <div>
            <span class="admin-brand">NOM-035</span>
            <span class="admin-brand-sub">Super Admin</span>
          </div>
        </div>
      </div>

      <nav class="admin-nav">
        <button v-for="item in adminNavItems" :key="item.id"
          class="admin-nav-item" :class="{ active: currentView === item.id }"
          @click="currentView = item.id">
          <span class="admin-nav-icon" v-html="item.icon"></span>
          <span class="admin-nav-label">{{ item.label }}</span>
          <span v-if="item.badge" class="admin-nav-badge">{{ item.badge }}</span>
        </button>
      </nav>

      <div class="admin-sidebar-footer">
        <div class="admin-user">
          <div class="admin-avatar">SA</div>
          <div>
            <span class="admin-user-name">{{ adminName }}</span>
            <span class="admin-user-role">{{ adminEmail }}</span>
          </div>
        </div>
      </div>
    </aside>

    <div class="admin-main">
      <header class="admin-topbar">
        <div>
          <h1 class="admin-page-title">{{ currentNavItem?.label }}</h1>
          <p class="admin-page-sub">Panel de administración del SaaS NOM-035</p>
        </div>
        <div class="admin-topbar-right">
          <div class="admin-env-badge">PRODUCCIÓN</div>
          <button class="admin-logout" @click="handleAdminLogout">Cerrar sesión</button>
        </div>
      </header>

      <main class="admin-content">

        <div v-if="currentView === 'dashboard'" class="view-dashboard">
          <div class="mrr-banner">
            <div class="mrr-left">
              <p class="mrr-label">MRR (Ingreso Mensual Recurrente)</p>
              <p class="mrr-value">${{ formatNum(saasStats.mrr) }} <span class="mrr-currency">MXN</span></p>
              <p class="mrr-trend">
                <svg viewBox="0 0 12 12" fill="none"><path d="M2 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                +{{ saasStats.mrrGrowth }}% vs mes anterior
              </p>
            </div>
            <div class="mrr-right">
              <div class="mrr-stat">
                <span class="mrr-stat-num">{{ saasStats.activeCompanies }}</span>
                <span class="mrr-stat-label">Empresas activas</span>
              </div>
              <div class="mrr-stat">
                <span class="mrr-stat-num">{{ saasStats.totalEmployees }}</span>
                <span class="mrr-stat-label">Empleados totales</span>
              </div>
              <div class="mrr-stat">
                <span class="mrr-stat-num">{{ saasStats.totalEvaluations }}</span>
                <span class="mrr-stat-label">Evaluaciones hoy</span>
              </div>
            </div>
          </div>

          <div class="admin-kpi-grid">
            <div v-for="kpi in adminKpis" :key="kpi.label" class="admin-kpi-card" :class="kpi.color">
              <div class="akpi-icon" v-html="kpi.icon"></div>
              <div class="akpi-value">{{ kpi.value }}</div>
              <div class="akpi-label">{{ kpi.label }}</div>
              <div class="akpi-trend" :class="kpi.up ? 'up' : 'down'">{{ kpi.trend }}</div>
            </div>
          </div>

          <div class="admin-panels-row">
            <div class="admin-panel">
              <h3 class="admin-panel-title">Distribución por Plan</h3>
              <div class="plan-dist-list">
                <div v-for="plan in planDistribution" :key="plan.name" class="plan-dist-row">
                  <div class="pd-left">
                    <span class="pd-dot" :style="{ background: plan.color }"></span>
                    <span class="pd-name">{{ plan.name }}</span>
                  </div>
                  <div class="pd-bar-wrap">
                    <div class="pd-bar">
                      <div class="pd-fill" :style="{ width: plan.pct + '%', background: plan.color }"></div>
                    </div>
                  </div>
                  <div class="pd-right">
                    <span class="pd-count">{{ plan.count }}</span>
                    <span class="pd-pct">{{ plan.pct }}%</span>
                    <span class="pd-revenue">${{ formatNum(plan.revenue) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="admin-panel">
              <h3 class="admin-panel-title">Actividad Reciente</h3>
              <div class="activity-list">
                <div v-for="act in recentActivity" :key="act.id" class="activity-item">
                  <div class="act-dot" :class="act.type"></div>
                  <div class="act-info">
                    <p class="act-text">{{ act.text }}</p>
                    <p class="act-time">{{ act.time }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="currentView === 'companies'" class="view-companies">
          <div class="companies-toolbar">
            <div class="search-wrap">
              <svg class="search-icon" viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.4"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              <input v-model="companySearch" placeholder="Buscar empresa..." class="search-input"/>
            </div>
            <div class="toolbar-right">
              <select v-model="companyFilter" class="filter-sel">
                <option value="">Todos los estados</option>
                <option value="active">Activas</option>
                <option value="suspended">Suspendidas</option>
                <option value="trial">En prueba</option>
              </select>
              <button class="btn-admin-primary" @click="openCompanyModal()">
                <svg viewBox="0 0 16 16" fill="none"><path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Nueva Empresa
              </button>
            </div>
          </div>

          <div class="companies-table-card">
            <table class="companies-table">
              <thead>
                <tr>
                  <th>Empresa</th><th>Plan</th><th>Empleados</th>
                  <th>Estado</th><th>Vence</th><th>MRR</th><th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="company in filteredCompanies" :key="company.id" class="company-row">
                  <td>
                    <div class="co-cell">
                      <div class="co-avatar">{{ company.name.charAt(0) }}</div>
                      <div>
                        <p class="co-name">{{ company.name }}</p>
                        <p class="co-rfc">{{ company.rfc }} · {{ company.email }}</p>
                      </div>
                    </div>
                  </td>
                  <td><span class="plan-chip" :class="`plan-${company.plan}`">{{ planLabel(company.plan) }}</span></td>
                  <td>
                    <div class="emp-usage">
                      <span class="eu-num">{{ company.employees_count }}</span>
                      <span class="eu-sep">/</span>
                      <span class="eu-max">{{ planMax(company.plan) }}</span>
                    </div>
                  </td>
                  <td>
                    <span class="status-badge" :class="`s-${company.status}`">
                      <span class="status-dot"></span>
                      {{ statusLabel(company.status) }}
                    </span>
                  </td>
                  <td class="td-date">{{ company.expires_at }}</td>
                  <td class="td-mrr">${{ formatNum(planPrice(company.plan)) }}</td>
                  <td>
                    <div class="co-actions">
                      <button class="co-btn view" @click="viewCompany(company)">
                        <svg viewBox="0 0 14 14" fill="none"><path d="M1 7s2-5 6-5 6 5 6 5-2 5-6 5-6-5-6-5z" stroke="currentColor" stroke-width="1.3"/><circle cx="7" cy="7" r="2" stroke="currentColor" stroke-width="1.3"/></svg>
                      </button>
                      <button class="co-btn edit" @click="openCompanyModal(company)">
                        <svg viewBox="0 0 14 14" fill="none"><path d="M9.5 1.5l3 3-7 7H2.5v-3l7-7z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/></svg>
                      </button>
                      <button v-if="company.status === 'active'" class="co-btn suspend" @click="toggleCompanyStatus(company)">
                        <svg viewBox="0 0 14 14" fill="none"><rect x="3" y="2" width="3" height="10" rx="1" stroke="currentColor" stroke-width="1.2"/><rect x="8" y="2" width="3" height="10" rx="1" stroke="currentColor" stroke-width="1.2"/></svg>
                      </button>
                      <button v-else class="co-btn activate" @click="toggleCompanyStatus(company)">
                        <svg viewBox="0 0 14 14" fill="none"><path d="M3 7l9 0M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div v-if="currentView === 'plans'" class="view-plans">
          <p class="plans-intro">Define los planes de suscripción que ofrecerás a las empresas.</p>
          <div class="plans-grid">
            <div v-for="plan in plans" :key="plan.id" class="plan-card" :class="{ featured: plan.slug === 'profesional' }">
              <div v-if="plan.slug === 'profesional'" class="plan-featured-badge">Más popular</div>
              <div class="plan-header">
                <h3 class="plan-name">{{ plan.name }}</h3>
                <div class="plan-price">
                  <span class="price-amount">${{ formatNum(plan.price) }}</span>
                  <span class="price-period">MXN/mes</span>
                </div>
              </div>
              <div class="plan-stats">
                <div class="ps-item">
                  <span class="ps-label">Empresas activas</span>
                  <span class="ps-val">{{ plan.active_companies }}</span>
                </div>
                <div class="ps-item">
                  <span class="ps-label">MRR del plan</span>
                  <span class="ps-val">${{ formatNum(plan.price * plan.active_companies) }}</span>
                </div>
              </div>
              <ul class="plan-features">
                <li v-for="f in plan.features" :key="f">
                  <svg viewBox="0 0 14 14" fill="none"><path d="M2 7l4 4 6-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  {{ f }}
                </li>
              </ul>
              <div class="plan-card-footer">
                <button class="btn-plan-edit" @click="editPlan(plan)">Editar plan</button>
                <span class="plan-limit">Máx. {{ plan.max_employees === -1 ? '∞' : plan.max_employees }} empleados</span>
              </div>
            </div>
          </div>
        </div>

      </main>
    </div>

    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showCompanyModal" class="modal-overlay" @mousedown.self="showCompanyModal = false">
          <div class="modal-box">
            <div class="modal-header">
              <h2 class="modal-title">{{ editingCompany ? 'Editar Empresa' : 'Nueva Empresa' }}</h2>
              <button class="modal-close" @click="showCompanyModal = false">
                <svg viewBox="0 0 20 20" fill="none"><path d="M5 5l10 10M15 5L5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
              </button>
            </div>
            <div class="modal-body">
              <div class="mf-row">
                <div class="mf-field">
                  <label>Nombre *</label>
                  <input v-model="companyForm.name" type="text" placeholder="Empresa SA de CV" class="mf-input"/>
                </div>
                <div class="mf-field">
                  <label>RFC</label>
                  <input v-model="companyForm.rfc" type="text" placeholder="EMP900101ABC" class="mf-input"/>
                </div>
              </div>
              <div class="mf-row">
                <div class="mf-field">
                  <label>Correo *</label>
                  <input v-model="companyForm.email" type="email" placeholder="admin@empresa.com" class="mf-input"/>
                </div>
                <div class="mf-field">
                  <label>Teléfono</label>
                  <input v-model="companyForm.phone" type="text" placeholder="55 1234 5678" class="mf-input"/>
                </div>
              </div>
              <div class="mf-row">
                <div class="mf-field">
                  <label>Plan *</label>
                  <select v-model="companyForm.plan_id" class="mf-select">
                    <option value="">Selecciona un plan</option>
                    <option v-for="p in plans" :key="p.id" :value="p.id">{{ p.name }} — ${{ formatNum(p.price) }}/mes</option>
                  </select>
                </div>
                <div class="mf-field">
                  <label>Estado</label>
                  <select v-model="companyForm.status" class="mf-select">
                    <option value="trial">Prueba (30 días)</option>
                    <option value="active">Activa</option>
                    <option value="suspended">Suspendida</option>
                  </select>
                </div>
              </div>
              <div class="mf-field">
                <label>Notas internas</label>
                <textarea v-model="companyForm.notes" placeholder="Notas..." class="mf-textarea" rows="2"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn-ghost" @click="showCompanyModal = false">Cancelar</button>
              <button class="btn-admin-primary" @click="saveCompany" :disabled="isSaving">
                <span v-if="!isSaving">{{ editingCompany ? 'Guardar' : 'Crear empresa' }}</span>
                <span v-else class="dots"><span></span><span></span><span></span></span>
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <Teleport to="body">
      <Transition name="toast">
        <div v-if="toast.show" class="toast" :class="toast.type">{{ toast.message }}</div>
      </Transition>
    </Teleport>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'

// ── Admin info real ──
const adminName  = ref('Super Admin')
const adminEmail = ref('admin@nom035.mx')

// ── Estado UI ──
const currentView      = ref('dashboard')
const isSaving         = ref(false)
const companySearch    = ref('')
const companyFilter    = ref('')
const showCompanyModal = ref(false)
const editingCompany   = ref(null)
const toast            = reactive({ show: false, message: '', type: 'success' })

const adminNavItems = [
  { id: 'dashboard', label: 'Dashboard',
    icon: `<svg viewBox="0 0 20 20" fill="none"><rect x="2" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="11" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="2" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="11" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/></svg>` },
  { id: 'companies', label: 'Empresas', badge: null,
    icon: `<svg viewBox="0 0 20 20" fill="none"><path d="M3 18V8l7-5 7 5v10H3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M8 18v-5h4v5" stroke="currentColor" stroke-width="1.4"/></svg>` },
  { id: 'plans', label: 'Planes',
    icon: `<svg viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>` },
]

const currentNavItem = computed(() => adminNavItems.find(n => n.id === currentView.value))

// ── Datos ──
const saasStats = ref({ mrr: 28750, mrrGrowth: 18, activeCompanies: 22, totalEmployees: 1840, totalEvaluations: 47 })

const adminKpis = computed(() => [
  { label: 'Empresas Activas', value: saasStats.value.activeCompanies, trend: '+3 este mes', up: true, color: 'akpi-indigo',
    icon: `<svg viewBox="0 0 20 20" fill="none"><path d="M3 18V8l7-5 7 5v10" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>` },
  { label: 'En Período Trial', value: 4, trend: '2 vencen esta semana', up: false, color: 'akpi-amber',
    icon: `<svg viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.4"/><path d="M10 6v4l3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>` },
  { label: 'Suspendidas', value: 2, trend: 'Requieren atención', up: false, color: 'akpi-red',
    icon: `<svg viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.4"/><path d="M7 7l6 6M13 7l-6 6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>` },
  { label: 'ARR Proyectado', value: '$345K', trend: '+22% YoY', up: true, color: 'akpi-green',
    icon: `<svg viewBox="0 0 20 20" fill="none"><path d="M3 15l4-5 4 3 4-7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>` },
])

const planDistribution = ref([
  { name: 'Básico',      count: 8,  pct: 36, color: '#6366F1', revenue: 8  * 499  },
  { name: 'Profesional', count: 11, pct: 50, color: '#0EA5E9', revenue: 11 * 1299 },
  { name: 'Empresarial', count: 3,  pct: 14, color: '#10B981', revenue: 3  * 2999 },
])

const recentActivity = ref([
  { id:1, text: 'Empresa "Grupo Alfa" se suscribió al plan Profesional', time: 'Hace 2 horas', type: 'new'     },
  { id:2, text: '"Constructora Benítez" renovó su suscripción Básica',   time: 'Hace 5 horas', type: 'renew'   },
  { id:3, text: '"TechSolutions" actualizó a plan Empresarial',           time: 'Ayer',         type: 'upgrade' },
  { id:4, text: '"Farmacia San José" venció su período de prueba',        time: 'Ayer',         type: 'warn'    },
  { id:5, text: '"Logística del Norte" fue suspendida por impago',        time: 'Hace 2 días',  type: 'suspend' },
])

const companies = ref([
  { id:1, name:'Grupo Alfa SA',        rfc:'GAL900101AAA', email:'admin@grupoalfa.com',   plan:'profesional', employees_count:42,  status:'active',    expires_at:'31/12/2025' },
  { id:2, name:'Constructora Benítez', rfc:'CBE850615BBB', email:'rh@benitez.com',        plan:'basico',      employees_count:18,  status:'active',    expires_at:'30/11/2025' },
  { id:3, name:'TechSolutions MX',     rfc:'TSM010101CCC', email:'ops@techsol.mx',        plan:'empresarial', employees_count:210, status:'active',    expires_at:'28/02/2026' },
  { id:4, name:'Farmacia San José',    rfc:'FSJ920303DDD', email:'admin@fsj.com',         plan:'basico',      employees_count:12,  status:'trial',     expires_at:'15/10/2025' },
  { id:5, name:'Logística del Norte',  rfc:'LNO780808EEE', email:'contabilidad@logn.com', plan:'profesional', employees_count:67,  status:'suspended', expires_at:'01/09/2025' },
  { id:6, name:'Clínica Vida Sana',    rfc:'CVS030303FFF', email:'admin@vidasana.com',    plan:'profesional', employees_count:55,  status:'active',    expires_at:'31/01/2026' },
])

const plans = ref([
  { id:1, name:'Básico',      slug:'basico',      price:499,  max_employees:25,  active_companies:8,  features:['Hasta 25 empleados','Guías I, II y III','3 reportes PDF/mes','Soporte email'] },
  { id:2, name:'Profesional', slug:'profesional', price:1299, max_employees:100, active_companies:11, features:['Hasta 100 empleados','Reportes ilimitados','Dashboard avanzado','Soporte prioritario'] },
  { id:3, name:'Empresarial', slug:'empresarial', price:2999, max_employees:500, active_companies:3,  features:['Hasta 500 empleados','Acceso API REST','Gestor dedicado','SLA 99.9%'] },
])

const companyForm = reactive({ name:'', rfc:'', email:'', phone:'', plan_id:'', status:'trial', notes:'' })

// ── Computed ──
const filteredCompanies = computed(() => {
  let data = [...companies.value]
  if (companyFilter.value) data = data.filter(c => c.status === companyFilter.value)
  if (companySearch.value) {
    const q = companySearch.value.toLowerCase()
    data = data.filter(c =>
      c.name.toLowerCase().includes(q) ||
      c.rfc?.toLowerCase().includes(q) ||
      c.email?.toLowerCase().includes(q)
    )
  }
  return data
})

// ── Helpers ──
function formatNum(n)    { return Number(n).toLocaleString('es-MX') }
function planLabel(slug) { return { basico:'Básico', profesional:'Profesional', empresarial:'Empresarial' }[slug] || slug }
function planMax(slug)   { return { basico:'25', profesional:'100', empresarial:'500' }[slug] || '—' }
function planPrice(slug) { return { basico:499, profesional:1299, empresarial:2999 }[slug] || 0 }
function statusLabel(s)  { return { active:'Activa', suspended:'Suspendida', trial:'Trial', cancelled:'Cancelada' }[s] || s }

function openCompanyModal(company = null) {
  editingCompany.value = company
  Object.assign(companyForm, { name:'', rfc:'', email:'', phone:'', plan_id:'', status:'trial', notes:'' })
  if (company) Object.assign(companyForm, { name: company.name, rfc: company.rfc, email: company.email, status: company.status })
  showCompanyModal.value = true
}

function viewCompany(company)      { showToast(`Detalle de ${company.name} — próximamente`) }
function editPlan(plan)            { showToast(`Editor de plan ${plan.name} — próximamente`) }

function toggleCompanyStatus(company) {
  const newStatus = company.status === 'active' ? 'suspended' : 'active'
  company.status = newStatus
  showToast(`Empresa ${newStatus === 'active' ? 'activada' : 'suspendida'} correctamente`)
}

async function saveCompany() {
  if (!companyForm.name || !companyForm.email) { showToast('Completa los campos requeridos', 'error'); return }
  isSaving.value = true
  await new Promise(r => setTimeout(r, 800))
  if (editingCompany.value) {
    const idx = companies.value.findIndex(c => c.id === editingCompany.value.id)
    if (idx >= 0) companies.value[idx] = { ...companies.value[idx], ...companyForm }
    showToast('Empresa actualizada')
  } else {
    companies.value.unshift({ id: Date.now(), ...companyForm, employees_count: 0, plan: 'basico', expires_at: '—' })
    showToast('Empresa creada correctamente')
  }
  showCompanyModal.value = false
  isSaving.value = false
}

function showToast(message, type = 'success') {
  toast.message = message; toast.type = type; toast.show = true
  setTimeout(() => toast.show = false, 3000)
}

// ── Logout ──
async function handleAdminLogout() {
  const token = localStorage.getItem('admin_token')
  if (token) {
    await fetch('/api/admin/logout', {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    })
  }
  localStorage.removeItem('admin_token')
  localStorage.removeItem('admin_user')
  window.location.href = '/admin/login'
}

// ── onMounted: verificar token y cargar datos reales ──
onMounted(async () => {
  const token = localStorage.getItem('admin_token')

  if (!token) {
    window.location.href = '/admin/login'
    return
  }

  try {
    const res = await fetch('/api/admin/me', {
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    })

    if (!res.ok) {
      localStorage.removeItem('admin_token')
      window.location.href = '/admin/login'
      return
    }

    const data = await res.json()
    adminName.value  = data.name
    adminEmail.value = data.email

  } catch {
    window.location.href = '/admin/login'
  }
})
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700;800&display=swap');
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.admin-shell { display: flex; min-height: 100vh; font-family: 'DM Sans', sans-serif; background: #0F0F1A; }

.admin-sidebar { width: 220px; flex-shrink: 0; background: #13131F; border-right: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; }
.admin-sidebar-header { padding: 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.06); }
.admin-logo { display: flex; align-items: center; gap: 10px; }
.admin-brand { display: block; font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 800; color: white; line-height: 1; }
.admin-brand-sub { display: block; font-size: 9px; color: #6366F1; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
.admin-nav { flex: 1; padding: 16px 10px; display: flex; flex-direction: column; gap: 2px; }
.admin-nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 9px; background: none; border: none; cursor: pointer; color: #475569; font-size: 13px; font-weight: 500; font-family: 'DM Sans', sans-serif; transition: all 0.18s; position: relative; white-space: nowrap; }
.admin-nav-item:hover { background: rgba(255,255,255,0.05); color: #94A3B8; }
.admin-nav-item.active { background: rgba(99,102,241,0.15); color: #A5B4FC; }
.admin-nav-item.active::before { content: ''; position: absolute; left: 0; top: 20%; height: 60%; width: 3px; background: #6366F1; border-radius: 0 3px 3px 0; }
.admin-nav-icon { width: 16px; height: 16px; display: flex; align-items: center; flex-shrink: 0; }
.admin-nav-icon svg { width: 16px; height: 16px; }
.admin-nav-label { flex: 1; }
.admin-nav-badge { background: #6366F1; color: white; font-size: 9px; font-weight: 700; padding: 1px 6px; border-radius: 100px; }
.admin-sidebar-footer { padding: 14px 10px; border-top: 1px solid rgba(255,255,255,0.06); }
.admin-user { display: flex; align-items: center; gap: 8px; }
.admin-avatar { width: 32px; height: 32px; background: linear-gradient(135deg, #6366F1, #8B5CF6); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; color: white; flex-shrink: 0; }
.admin-user-name { display: block; font-size: 12px; font-weight: 600; color: #E2E8F0; }
.admin-user-role { display: block; font-size: 10px; color: #475569; }

.admin-main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
.admin-topbar { background: #13131F; border-bottom: 1px solid rgba(255,255,255,0.06); padding: 0 28px; height: 60px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
.admin-page-title { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; color: white; }
.admin-page-sub { font-size: 11px; color: #475569; margin-top: 1px; }
.admin-topbar-right { display: flex; align-items: center; gap: 12px; }
.admin-env-badge { background: rgba(99,102,241,0.2); color: #A5B4FC; font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 100px; letter-spacing: 1px; }
.admin-logout { background: none; border: 1px solid rgba(255,255,255,0.1); color: #64748B; font-size: 12px; padding: 6px 12px; border-radius: 7px; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all 0.2s; }
.admin-logout:hover { border-color: #EF4444; color: #EF4444; }
.admin-content { flex: 1; padding: 24px; overflow-y: auto; }

.mrr-banner { background: linear-gradient(130deg, #1E1B4B, #312E81, #4338CA); border-radius: 18px; padding: 28px 32px; display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 20px; }
.mrr-label { font-size: 12px; color: rgba(255,255,255,0.6); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 1px; }
.mrr-value { font-family: 'Syne', sans-serif; font-size: 42px; font-weight: 800; color: white; line-height: 1; }
.mrr-currency { font-size: 18px; color: rgba(255,255,255,0.6); }
.mrr-trend { display: flex; align-items: center; gap: 4px; font-size: 13px; color: #86EFAC; margin-top: 8px; font-weight: 600; }
.mrr-trend svg { width: 14px; height: 14px; }
.mrr-right { display: flex; gap: 32px; }
.mrr-stat { text-align: center; }
.mrr-stat-num { display: block; font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: white; }
.mrr-stat-label { display: block; font-size: 11px; color: rgba(255,255,255,0.55); margin-top: 3px; }

.admin-kpi-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 20px; }
.admin-kpi-card { background: #13131F; border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 18px; }
.akpi-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
.akpi-icon svg { width: 18px; height: 18px; }
.akpi-indigo .akpi-icon { background: rgba(99,102,241,0.15); color: #A5B4FC; }
.akpi-amber  .akpi-icon { background: rgba(245,158,11,0.15);  color: #FCD34D; }
.akpi-red    .akpi-icon { background: rgba(239,68,68,0.15);   color: #FCA5A5; }
.akpi-green  .akpi-icon { background: rgba(16,185,129,0.15);  color: #6EE7B7; }
.akpi-value { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: white; line-height: 1; margin-bottom: 4px; }
.akpi-label { font-size: 12px; color: #475569; margin-bottom: 8px; }
.akpi-trend { font-size: 11px; font-weight: 600; }
.akpi-trend.up   { color: #6EE7B7; }
.akpi-trend.down { color: #FCA5A5; }

.admin-panels-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.admin-panel { background: #13131F; border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 20px; }
.admin-panel-title { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 800; color: white; margin-bottom: 16px; }

.plan-dist-list { display: flex; flex-direction: column; gap: 14px; }
.plan-dist-row { display: flex; align-items: center; gap: 10px; }
.pd-left { display: flex; align-items: center; gap: 7px; width: 100px; flex-shrink: 0; }
.pd-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.pd-name { font-size: 12px; color: #94A3B8; }
.pd-bar-wrap { flex: 1; }
.pd-bar { height: 6px; background: rgba(255,255,255,0.06); border-radius: 100px; overflow: hidden; }
.pd-fill { height: 100%; border-radius: 100px; transition: width 1s ease; }
.pd-right { display: flex; align-items: center; gap: 8px; }
.pd-count { font-size: 13px; font-weight: 700; color: white; width: 20px; text-align: right; }
.pd-pct { font-size: 10px; color: #475569; width: 30px; }
.pd-revenue { font-size: 11px; color: #6EE7B7; font-weight: 600; width: 64px; text-align: right; }

.activity-list { display: flex; flex-direction: column; gap: 12px; }
.activity-item { display: flex; align-items: flex-start; gap: 10px; }
.act-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
.act-dot.new     { background: #6EE7B7; }
.act-dot.renew   { background: #A5B4FC; }
.act-dot.upgrade { background: #FCD34D; }
.act-dot.warn    { background: #FCA5A5; }
.act-dot.suspend { background: #EF4444; }
.act-text { font-size: 12px; color: #94A3B8; line-height: 1.5; }
.act-time { font-size: 10px; color: #334155; margin-top: 2px; }

.view-companies { display: flex; flex-direction: column; gap: 16px; }
.companies-toolbar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.search-wrap { position: relative; flex: 1; min-width: 240px; }
.search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: #475569; pointer-events: none; }
.search-input { width: 100%; padding: 9px 14px 9px 34px; background: #13131F; border: 1px solid rgba(255,255,255,0.1); border-radius: 9px; color: #E2E8F0; font-family: 'DM Sans', sans-serif; font-size: 13px; outline: none; transition: border-color 0.2s; }
.search-input:focus { border-color: #6366F1; }
.search-input::placeholder { color: #334155; }
.toolbar-right { display: flex; gap: 10px; align-items: center; }
.filter-sel { padding: 8px 28px 8px 12px; background: #13131F; border: 1px solid rgba(255,255,255,0.1); border-radius: 9px; color: #94A3B8; font-family: 'DM Sans', sans-serif; font-size: 13px; appearance: none; cursor: pointer; outline: none; }
.btn-admin-primary { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; background: #6366F1; color: white; border: none; border-radius: 9px; font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
.btn-admin-primary:hover { background: #4F46E5; transform: translateY(-1px); }
.btn-admin-primary svg { width: 13px; height: 13px; }
.btn-admin-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

.companies-table-card { background: #13131F; border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; overflow: hidden; }
.companies-table { width: 100%; border-collapse: collapse; }
.companies-table thead tr { background: #0F0F1A; }
.companies-table th { padding: 10px 14px; text-align: left; font-size: 10px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(255,255,255,0.05); }
.company-row { border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.15s; }
.company-row:hover { background: rgba(255,255,255,0.02); }
.company-row:last-child { border-bottom: none; }
.companies-table td { padding: 12px 14px; font-size: 12px; color: #64748B; vertical-align: middle; }
.co-cell { display: flex; align-items: center; gap: 10px; }
.co-avatar { width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0; background: linear-gradient(135deg, #4338CA, #6366F1); display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 800; color: white; }
.co-name { font-size: 13px; font-weight: 600; color: #E2E8F0; }
.co-rfc  { font-size: 10px; color: #334155; margin-top: 1px; }
.plan-chip { font-size: 10px; font-weight: 700; padding: 3px 9px; border-radius: 100px; }
.plan-basico      { background: rgba(99,102,241,0.15); color: #A5B4FC; }
.plan-profesional { background: rgba(14,165,233,0.15); color: #7DD3FC; }
.plan-empresarial { background: rgba(16,185,129,0.15); color: #6EE7B7; }
.emp-usage { display: flex; align-items: center; gap: 3px; }
.eu-num { font-weight: 700; color: #E2E8F0; font-size: 13px; }
.eu-sep { color: #334155; }
.eu-max { color: #334155; font-size: 11px; }
.status-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 10px; font-weight: 700; padding: 3px 9px; border-radius: 100px; }
.status-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
.s-active    { background: rgba(16,185,129,0.15); color: #6EE7B7; }
.s-suspended { background: rgba(239,68,68,0.15);  color: #FCA5A5; }
.s-trial     { background: rgba(245,158,11,0.15); color: #FCD34D; }
.s-cancelled { background: rgba(100,116,139,0.15);color: #94A3B8; }
.td-date { color: #475569; white-space: nowrap; }
.td-mrr  { font-weight: 700; color: #6EE7B7; }
.co-actions { display: flex; gap: 4px; }
.co-btn { width: 28px; height: 28px; border-radius: 7px; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.18s; }
.co-btn svg { width: 12px; height: 12px; }
.co-btn.view     { background: rgba(99,102,241,0.15); color: #A5B4FC; }
.co-btn.view:hover     { background: rgba(99,102,241,0.3); }
.co-btn.edit     { background: rgba(14,165,233,0.15); color: #7DD3FC; }
.co-btn.edit:hover     { background: rgba(14,165,233,0.3); }
.co-btn.suspend  { background: rgba(245,158,11,0.15); color: #FCD34D; }
.co-btn.suspend:hover  { background: rgba(245,158,11,0.3); }
.co-btn.activate { background: rgba(16,185,129,0.15); color: #6EE7B7; }
.co-btn.activate:hover { background: rgba(16,185,129,0.3); }

.plans-intro { font-size: 14px; color: #475569; margin-bottom: 20px; }
.plans-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
.plan-card { background: #13131F; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 24px; position: relative; transition: transform 0.2s; }
.plan-card:hover { transform: translateY(-2px); }
.plan-card.featured { border-color: #6366F1; background: linear-gradient(160deg, #13131F, #1E1B4B); }
.plan-featured-badge { position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: #6366F1; color: white; font-size: 10px; font-weight: 700; padding: 3px 12px; border-radius: 100px; white-space: nowrap; }
.plan-name { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; color: white; margin-bottom: 8px; }
.plan-price { display: flex; align-items: baseline; gap: 4px; margin-bottom: 16px; }
.price-amount { font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 800; color: #A5B4FC; }
.price-period { font-size: 13px; color: #475569; }
.plan-stats { display: flex; gap: 16px; padding: 12px 0; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); margin-bottom: 16px; }
.ps-item { flex: 1; }
.ps-label { display: block; font-size: 10px; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
.ps-val { display: block; font-size: 16px; font-weight: 700; color: #E2E8F0; }
.plan-features { list-style: none; display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
.plan-features li { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #64748B; }
.plan-features li svg { width: 14px; height: 14px; color: #6366F1; flex-shrink: 0; }
.plan-card-footer { display: flex; align-items: center; justify-content: space-between; }
.btn-plan-edit { padding: 7px 14px; background: rgba(99,102,241,0.15); color: #A5B4FC; border: none; border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.btn-plan-edit:hover { background: rgba(99,102,241,0.3); }
.plan-limit { font-size: 11px; color: #334155; }

.modal-overlay { position: fixed; inset: 0; z-index: 999; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; padding: 20px; }
.modal-box { background: #13131F; border: 1px solid rgba(255,255,255,0.1); border-radius: 18px; width: 100%; max-width: 540px; box-shadow: 0 32px 80px rgba(0,0,0,0.5); }
.modal-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.06); }
.modal-title { font-family: 'Syne', sans-serif; font-size: 17px; font-weight: 800; color: white; }
.modal-close { background: none; border: none; cursor: pointer; color: #475569; padding: 4px; border-radius: 6px; transition: all 0.18s; display: flex; }
.modal-close:hover { color: white; background: rgba(255,255,255,0.08); }
.modal-close svg { width: 17px; height: 17px; }
.modal-body { padding: 20px 24px; display: flex; flex-direction: column; gap: 14px; }
.mf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.mf-field { display: flex; flex-direction: column; gap: 5px; }
.mf-field label { font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
.mf-input, .mf-select, .mf-textarea { padding: 9px 12px; background: #0F0F1A; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #E2E8F0; font-family: 'DM Sans', sans-serif; font-size: 13px; outline: none; transition: border-color 0.2s; }
.mf-input:focus, .mf-select:focus, .mf-textarea:focus { border-color: #6366F1; }
.mf-select { appearance: none; cursor: pointer; }
.mf-textarea { resize: none; }
.modal-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 16px 24px; border-top: 1px solid rgba(255,255,255,0.06); }
.btn-ghost { padding: 9px 16px; background: none; color: #475569; border: 1px solid rgba(255,255,255,0.1); border-radius: 9px; font-family: 'DM Sans', sans-serif; font-size: 13px; cursor: pointer; transition: all 0.2s; }
.btn-ghost:hover { border-color: rgba(255,255,255,0.2); color: #94A3B8; }

.dots { display: inline-flex; gap: 4px; }
.dots span { width: 5px; height: 5px; background: white; border-radius: 50%; animation: bounce 1s ease-in-out infinite; }
.dots span:nth-child(2) { animation-delay: 0.15s; }
.dots span:nth-child(3) { animation-delay: 0.3s; }
@keyframes bounce { 0%,80%,100%{transform:scale(0.8);opacity:.5} 40%{transform:scale(1.1);opacity:1} }

.toast { position: fixed; bottom: 24px; right: 24px; z-index: 9999; padding: 13px 20px; border-radius: 11px; font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500; box-shadow: 0 8px 24px rgba(0,0,0,0.4); }
.toast.success { background: #1E1B4B; color: #A5B4FC; border: 1px solid rgba(99,102,241,0.3); }
.toast.error   { background: #1F1315; color: #FCA5A5; border: 1px solid rgba(239,68,68,0.3); }
.toast-enter-active, .toast-leave-active { transition: all 0.3s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(12px); }
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .modal-box, .modal-leave-to .modal-box { transform: scale(0.95) translateY(10px); }

@media (max-width: 1100px) {
  .admin-kpi-grid { grid-template-columns: repeat(2,1fr); }
  .admin-panels-row, .plans-grid { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
  .admin-sidebar { display: none; }
  .admin-kpi-grid { grid-template-columns: 1fr; }
  .mf-row { grid-template-columns: 1fr; }
}
</style>