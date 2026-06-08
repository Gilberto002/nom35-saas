<template>
  <div class="ob-root">

    <!-- Ticker -->
    <div class="ob-ticker">
      <div class="ob-ticker-track">
        <span v-for="i in 3" :key="i" class="ob-ticker-inner">
          <span class="ob-ticker-item">NOM-035-STPS-2018</span>
          <span class="ob-ticker-item">3 días gratis</span>
          <span class="ob-ticker-item">Sin tarjeta de crédito</span>
          <span class="ob-ticker-item">Cumplimiento normativo</span>
          <span class="ob-ticker-item">Plataforma certificada</span>
        </span>
      </div>
    </div>

    <!-- Nav -->
    <header class="ob-nav">
      <a href="/" class="ob-logo">
        <span class="ob-logo-dot"></span>
        NOM-035 SaaS
      </a>
      <div class="ob-nav-right">
        <span class="ob-nav-hint">¿Ya tienes cuenta?</span>
        <a href="/login" class="ob-nav-login">Iniciar sesión →</a>
      </div>
    </header>

    <!-- Step bar -->
    <div class="ob-stepbar">
      <div
        v-for="(s, i) in steps"
        :key="i"
        class="ob-stepbar-item"
        :class="{
          'is-active':    currentStep === i,
          'is-done':      currentStep > i,
        }"
      >
        <div class="ob-step-num">
          <svg v-if="currentStep > i" viewBox="0 0 14 14" fill="none">
            <path d="M2 7l4 4 6-7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span v-else>{{ i + 1 }}</span>
        </div>
        <span class="ob-step-label">{{ s }}</span>
        <div v-if="i < steps.length - 1" class="ob-step-connector" :class="{ 'is-filled': currentStep > i }"></div>
      </div>
    </div>

    <!-- ════════════════════════
         PASO 0: Elegir plan
    ════════════════════════ -->
    <Transition name="slide" mode="out-in">
    <div v-if="currentStep === 0" key="step0" class="ob-stage">

      <div class="ob-stage-head">
        <p class="ob-eyebrow">Paso 1 de 3</p>
        <h1 class="ob-title">ELIGE TU<br><span class="ob-title-outline">PLAN</span></h1>
        <p class="ob-subtitle">3 días gratis en cualquier plan. Sin tarjeta de crédito.</p>
      </div>

      <!-- Toggle mensual/anual -->
      <div class="ob-billing-toggle">
        <span :class="['ob-billing-label', { 'is-active': billing === 'monthly' }]">Mensual</span>
        <button class="ob-toggle-btn" @click="billing = billing === 'monthly' ? 'yearly' : 'monthly'" :class="{ 'is-yearly': billing === 'yearly' }">
          <span class="ob-toggle-thumb"></span>
        </button>
        <span :class="['ob-billing-label', { 'is-active': billing === 'yearly' }]">
          Anual
          <span class="ob-save-badge">−20%</span>
        </span>
      </div>

      <!-- Plans -->
      <div class="ob-plans">
        <div
          v-for="plan in plans"
          :key="plan.id"
          class="ob-plan"
          :class="{
            'is-selected': selectedPlan === plan.id,
            'is-featured': plan.featured,
          }"
          @click="selectedPlan = plan.id"
        >
          <div v-if="plan.featured" class="ob-plan-badge">★ Más popular</div>

          <div class="ob-plan-top">
            <div>
              <div class="ob-plan-name">{{ plan.name }}</div>
              <div class="ob-plan-tagline">{{ plan.tagline }}</div>
            </div>
            <div class="ob-plan-radio" :class="{ 'is-on': selectedPlan === plan.id }">
              <svg v-if="selectedPlan === plan.id" viewBox="0 0 12 12" fill="none">
                <path d="M2 6l3 3 5-6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </div>

          <div class="ob-plan-price">
            ${{ billing === 'yearly' ? plan.priceYearly : plan.price }}
            <span class="ob-plan-period">MXN/mes</span>
          </div>

          <div v-if="billing === 'yearly'" class="ob-plan-saving">
            Ahorras ${{ (plan.price - plan.priceYearly) * 12 }}/año
          </div>

          <div class="ob-plan-divider"></div>

          <ul class="ob-plan-feats">
            <li v-for="f in plan.features" :key="f.text" :class="{ 'is-off': !f.included }">
              <span class="ob-feat-icon">{{ f.included ? '✓' : '✗' }}</span>
              {{ f.text }}
            </li>
          </ul>
        </div>
      </div>

      <div class="ob-trial-strip">
        <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/><path d="M8 5v4M8 11h.01" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
        Todos los planes incluyen <strong>3 días de prueba gratuita</strong>. Cancela cuando quieras.
      </div>

      <div class="ob-foot">
        <div></div>
        <button class="ob-btn-primary" @click="goToStep(1)" :disabled="!selectedPlan">
          Continuar
          <svg viewBox="0 0 16 16" fill="none"><path d="M3 8h10M8 3l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
    </div>
    </Transition>

    <!-- ════════════════════════
         PASO 1: Datos
    ════════════════════════ -->
    <Transition name="slide" mode="out-in">
    <div v-if="currentStep === 1" key="step1" class="ob-stage ob-stage--narrow">

      <div class="ob-stage-head">
        <p class="ob-eyebrow">Paso 2 de 3</p>
        <h1 class="ob-title">CREA TU<br><span class="ob-title-outline">CUENTA</span></h1>
        <p class="ob-subtitle">Configura los datos de tu empresa y el acceso de administrador.</p>
      </div>

      <div class="ob-card">

        <!-- Empresa -->
        <div class="ob-section">
          <div class="ob-section-label">
            <div class="ob-section-icon">
              <svg viewBox="0 0 16 16" fill="none"><path d="M1 14V7l7-5 7 5v7H1z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M6 14V9h4v5" stroke="currentColor" stroke-width="1.4"/></svg>
            </div>
            Datos de la empresa
          </div>

          <div class="ob-form-row">
            <div class="ob-field" :class="{ 'has-error': errors.company_name }">
              <label>Nombre de la empresa <span class="ob-req">*</span></label>
              <input v-model="form.company_name" type="text" placeholder="Empresa SA de CV" class="ob-input"/>
              <span v-if="errors.company_name" class="ob-error-msg">{{ errors.company_name }}</span>
            </div>
            <div class="ob-field">
              <label>RFC</label>
              <input v-model="form.rfc" type="text" placeholder="EMP900101ABC" class="ob-input" maxlength="13"/>
            </div>
          </div>

          <div class="ob-form-row">
            <div class="ob-field" :class="{ 'has-error': errors.company_email }">
              <label>Correo de la empresa <span class="ob-req">*</span></label>
              <input v-model="form.company_email" type="email" placeholder="contacto@empresa.com" class="ob-input"/>
              <span v-if="errors.company_email" class="ob-error-msg">{{ errors.company_email }}</span>
            </div>
            <div class="ob-field">
              <label>Teléfono</label>
              <input v-model="form.phone" type="text" placeholder="55 1234 5678" class="ob-input"/>
            </div>
          </div>

          <div class="ob-field">
            <label>Industria</label>
            <select v-model="form.industry" class="ob-input ob-select">
              <option value="">Selecciona la industria</option>
              <option v-for="ind in industries" :key="ind" :value="ind">{{ ind }}</option>
            </select>
          </div>
        </div>

        <div class="ob-card-divider"></div>

        <!-- Administrador -->
        <div class="ob-section">
          <div class="ob-section-label">
            <div class="ob-section-icon">
              <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="5.5" r="3" stroke="currentColor" stroke-width="1.4"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
            </div>
            Cuenta del administrador
          </div>

          <div class="ob-field" :class="{ 'has-error': errors.admin_name }">
            <label>Nombre completo <span class="ob-req">*</span></label>
            <input v-model="form.admin_name" type="text" placeholder="María González" class="ob-input"/>
            <span v-if="errors.admin_name" class="ob-error-msg">{{ errors.admin_name }}</span>
          </div>

          <div class="ob-field" :class="{ 'has-error': errors.admin_email }">
            <label>Correo del administrador <span class="ob-req">*</span></label>
            <input v-model="form.admin_email" type="email" placeholder="admin@empresa.com" class="ob-input"/>
            <span v-if="errors.admin_email" class="ob-error-msg">{{ errors.admin_email }}</span>
          </div>

          <div class="ob-form-row">
            <div class="ob-field" :class="{ 'has-error': errors.password }">
              <label>Contraseña <span class="ob-req">*</span></label>
              <div class="ob-pass-wrap">
                <input v-model="form.password" :type="showPass ? 'text' : 'password'" placeholder="Mínimo 8 caracteres" class="ob-input"/>
                <button type="button" class="ob-pass-eye" @click="showPass = !showPass">
                  <svg v-if="!showPass" viewBox="0 0 16 16" fill="none"><path d="M2 8s2-4 6-4 6 4 6 4-2 4-6 4-6-4-6-4z" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.3"/></svg>
                  <svg v-else viewBox="0 0 16 16" fill="none"><path d="M2 2l12 12M7 6.1A2 2 0 0110 9M4.5 4.7C3 5.8 2 7.5 2 8s2 4 6 4c1.2 0 2.3-.3 3.2-.8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M8.8 3.1C11.5 3.6 14 6 14 8c0 .7-.4 1.5-1 2.2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                </button>
              </div>
              <div v-if="form.password" class="ob-strength">
                <div class="ob-strength-bars">
                  <div v-for="i in 4" :key="i" class="ob-strength-bar"
                    :class="{ 'is-filled': passwordStrength >= i, [`lv-${passwordStrength}`]: passwordStrength >= i }">
                  </div>
                </div>
                <span class="ob-strength-label" :class="`lv-${passwordStrength}`">{{ passwordStrengthLabel }}</span>
              </div>
              <span v-if="errors.password" class="ob-error-msg">{{ errors.password }}</span>
            </div>

            <div class="ob-field" :class="{ 'has-error': errors.password_confirmation }">
              <label>Confirmar contraseña <span class="ob-req">*</span></label>
              <input v-model="form.password_confirmation" :type="showPass ? 'text' : 'password'" placeholder="Repite tu contraseña" class="ob-input"/>
              <span v-if="errors.password_confirmation" class="ob-error-msg">{{ errors.password_confirmation }}</span>
            </div>
          </div>
        </div>

        <!-- Plan reminder -->
        <div class="ob-plan-reminder">
          <div class="ob-pr-info">
            <div class="ob-pr-dot"></div>
            <div>
              <p class="ob-pr-title">Plan {{ selectedPlanData?.name }} — 3 días gratis</p>
              <p class="ob-pr-sub">Luego ${{ selectedPlanData?.price }} MXN/mes · Cancela cuando quieras</p>
            </div>
          </div>
          <button class="ob-pr-change" @click="goToStep(0)">Cambiar plan</button>
        </div>

        <!-- Terms -->
        <label class="ob-terms" :class="{ 'has-error': errors.terms }">
          <input type="checkbox" v-model="form.terms" class="ob-terms-check"/>
          <span class="ob-terms-box"></span>
          <span class="ob-terms-text">
            Acepto los <a href="#" class="ob-link">Términos de Servicio</a> y el
            <a href="#" class="ob-link">Aviso de Privacidad</a>
          </span>
        </label>
        <span v-if="errors.terms" class="ob-error-msg">{{ errors.terms }}</span>
      </div>

      <div class="ob-foot">
        <button class="ob-btn-back" @click="goToStep(0)">
          <svg viewBox="0 0 16 16" fill="none"><path d="M13 8H3M8 3l-5 5 5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Atrás
        </button>
        <button class="ob-btn-primary" @click="submitRegistration" :disabled="isLoading">
          <span v-if="!isLoading">Crear cuenta gratis</span>
          <span v-else class="ob-dots"><span></span><span></span><span></span></span>
          <svg v-if="!isLoading" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M8 3l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
    </div>
    </Transition>

    <!-- ════════════════════════
         PASO 2: Éxito
    ════════════════════════ -->
    <Transition name="slide" mode="out-in">
    <div v-if="currentStep === 2" key="step2" class="ob-stage ob-stage--narrow">
      <div class="ob-success">

        <div class="ob-success-check">
          <svg viewBox="0 0 72 72" fill="none">
            <circle cx="36" cy="36" r="34" fill="#0A0A0A"/>
            <path d="M20 36l12 12 20-22" stroke="#E8180C" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" class="ob-check-draw"/>
          </svg>
        </div>

        <p class="ob-eyebrow" style="text-align:center">¡Todo listo!</p>
        <h1 class="ob-title" style="text-align:center">CUENTA<br><span class="ob-title-outline">CREADA</span></h1>
        <p class="ob-success-sub">
          Bienvenido a NOM-035 SaaS, <strong>{{ form.admin_name }}</strong>.<br>
          Tu período de prueba de 3 días ha comenzado.
        </p>

        <!-- Summary grid -->
        <div class="ob-summary">
          <div class="ob-summary-item">
            <span class="ob-summary-label">Empresa</span>
            <span class="ob-summary-value">{{ form.company_name }}</span>
          </div>
          <div class="ob-summary-item">
            <span class="ob-summary-label">Plan activo</span>
            <span class="ob-summary-value">{{ selectedPlanData?.name }}</span>
          </div>
          <div class="ob-summary-item">
            <span class="ob-summary-label">Prueba gratis hasta</span>
            <span class="ob-summary-value">{{ trialEndDate }}</span>
          </div>
          <div class="ob-summary-item">
            <span class="ob-summary-label">Correo de acceso</span>
            <span class="ob-summary-value">{{ form.admin_email }}</span>
          </div>
        </div>

        <!-- Next steps -->
        <div class="ob-next">
          <div class="ob-next-title">Próximos pasos recomendados</div>
          <div class="ob-next-list">
            <div v-for="(ns, i) in nextSteps" :key="i" class="ob-next-item">
              <div class="ob-next-num">{{ i + 1 }}</div>
              <div>
                <p class="ob-next-label">{{ ns.label }}</p>
                <p class="ob-next-sub">{{ ns.sub }}</p>
              </div>
            </div>
          </div>
        </div>

        <button class="ob-btn-enter" @click="enterDashboard">
          <svg viewBox="0 0 16 16" fill="none"><path d="M3 8h10M8 3l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Entrar al panel
        </button>

        <p class="ob-success-note">
          Enviamos un correo de confirmación a <strong>{{ form.admin_email }}</strong>
        </p>
      </div>
    </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

const currentStep = ref(0)
const selectedPlan = ref(null)
const billing = ref('monthly')
const showPass = ref(false)
const isLoading = ref(false)

const steps = ['Elige tu plan', 'Crea tu cuenta', '¡Listo!']

const form = reactive({
  company_name: '', rfc: '', company_email: '', phone: '', industry: '',
  admin_name: '', admin_email: '', password: '', password_confirmation: '',
  terms: false,
})
const errors = reactive({})

const plans = [
  {
    id: 1, name: 'Básico', tagline: 'Para empresas pequeñas',
    price: 499, priceYearly: 399, featured: false,
    features: [
      { text: 'Hasta 25 empleados',           included: true  },
      { text: 'Guías I, II y III',             included: true  },
      { text: 'Evaluaciones ilimitadas',        included: true  },
      { text: '3 reportes PDF por mes',         included: true  },
      { text: 'Dashboard básico',               included: true  },
      { text: 'Reportes ilimitados',            included: false },
      { text: 'Acceso API',                     included: false },
    ],
  },
  {
    id: 2, name: 'Profesional', tagline: 'Para empresas medianas',
    price: 1299, priceYearly: 1039, featured: true,
    features: [
      { text: 'Hasta 100 empleados',            included: true  },
      { text: 'Guías I, II y III',              included: true  },
      { text: 'Evaluaciones ilimitadas',         included: true  },
      { text: 'Reportes PDF ilimitados',         included: true  },
      { text: 'Dashboard avanzado',              included: true  },
      { text: 'Resultados por área',             included: true  },
      { text: 'Acceso API',                      included: false },
    ],
  },
  {
    id: 3, name: 'Empresarial', tagline: 'Para grandes empresas',
    price: 2999, priceYearly: 2399, featured: false,
    features: [
      { text: 'Hasta 500 empleados',             included: true  },
      { text: 'Guías I, II y III',               included: true  },
      { text: 'Evaluaciones ilimitadas',          included: true  },
      { text: 'Reportes PDF ilimitados',          included: true  },
      { text: 'Dashboard ejecutivo',              included: true  },
      { text: 'Acceso API REST',                  included: true  },
      { text: 'Gestor de cuenta dedicado',        included: true  },
    ],
  },
]

const industries = [
  'Manufactura', 'Servicios', 'Salud', 'Educación', 'Construcción',
  'Logística y transporte', 'Retail / Comercio', 'Tecnología', 'Finanzas',
  'Alimentos y bebidas', 'Gobierno', 'Otro',
]

const nextSteps = [
  { label: 'Crea tus áreas',       sub: 'Organiza tu empresa por departamentos' },
  { label: 'Registra empleados',   sub: 'Agrega a los trabajadores de tu empresa' },
  { label: 'Aplica evaluaciones',  sub: 'Comienza con la Guía II para todos' },
  { label: 'Genera tu reporte',    sub: 'Descarga el PDF para auditoría STPS' },
]

const selectedPlanData = computed(() => plans.find(p => p.id === selectedPlan.value))

const passwordStrength = computed(() => {
  const p = form.password
  if (!p) return 0
  let score = 0
  if (p.length >= 8)          score++
  if (/[A-Z]/.test(p))        score++
  if (/[0-9]/.test(p))        score++
  if (/[^A-Za-z0-9]/.test(p)) score++
  return score
})
const passwordStrengthLabel = computed(() => {
  return ['', 'Débil', 'Regular', 'Buena', 'Muy fuerte'][passwordStrength.value]
})

const trialEndDate = computed(() => {
  const d = new Date()
  d.setDate(d.getDate() + 3)
  return d.toLocaleDateString('es-MX', { day: '2-digit', month: 'long', year: 'numeric' })
})

function goToStep(step) {
  currentStep.value = step
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function validateStep1() {
  Object.keys(errors).forEach(k => delete errors[k])
  let valid = true
  if (!form.company_name.trim()) { errors.company_name = 'El nombre es requerido'; valid = false }
  if (!form.company_email.trim()) { errors.company_email = 'El correo es requerido'; valid = false }
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.company_email)) { errors.company_email = 'Correo inválido'; valid = false }
  if (!form.admin_name.trim()) { errors.admin_name = 'Tu nombre es requerido'; valid = false }
  if (!form.admin_email.trim()) { errors.admin_email = 'Tu correo es requerido'; valid = false }
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.admin_email)) { errors.admin_email = 'Correo inválido'; valid = false }
  if (!form.password) { errors.password = 'La contraseña es requerida'; valid = false }
  else if (form.password.length < 8) { errors.password = 'Mínimo 8 caracteres'; valid = false }
  if (form.password !== form.password_confirmation) { errors.password_confirmation = 'Las contraseñas no coinciden'; valid = false }
  if (!form.terms) { errors.terms = 'Debes aceptar los términos'; valid = false }
  return valid
}

async function submitRegistration() {
  if (!validateStep1()) return
  isLoading.value = true
  try {
    await axios.post('/api/register', {
      company_name:  form.company_name,
      rfc:           form.rfc,
      company_email: form.company_email,
      phone:         form.phone,
      industry:      form.industry,
      admin_name:    form.admin_name,
      admin_email:   form.admin_email,
      password:      form.password,
      password_confirmation: form.password_confirmation,
      plan_id:  selectedPlan.value,
      billing:  billing.value,
    })
   goToStep(2)
setTimeout(() => router.visit('/login'), 3000)
  } catch (e) {
    if (e.response?.status === 422) {
      const errs = e.response.data.errors || {}
      Object.entries(errs).forEach(([k, v]) => { errors[k] = v[0] })
      window.scrollTo({ top: 300, behavior: 'smooth' })
    } else {
      errors.company_name = 'Error al crear la cuenta. Intenta de nuevo.'
    }
  } finally {
    isLoading.value = false
  }
}

function enterDashboard() {
  window.location.href = '/dashboard'
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --black: #0A0A0A;
  --white: #FFFFFF;
  --red:   #E8180C;
  --gray:  #F2F2F0;
  --gray2: #D4D4D0;
  --muted: #7A7A72;
  --border: 2px solid var(--black);
}

/* ─── Root ─── */
.ob-root {
  min-height: 100vh;
  background: var(--white);
  font-family: 'DM Sans', sans-serif;
  color: var(--black);
  overflow-x: hidden;
}

/* ─── Ticker ─── */
.ob-ticker {
  background: var(--red);
  color: var(--white);
  padding: 7px 0;
  overflow: hidden;
}
.ob-ticker-track {
  display: flex;
  white-space: nowrap;
  animation: ob-ticker 22s linear infinite;
}
.ob-ticker-inner { display: flex; }
.ob-ticker-item {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 12px;
  letter-spacing: 2.5px;
  padding: 0 28px;
}
.ob-ticker-item::after { content: ' ✦'; opacity: 0.6; }
@keyframes ob-ticker {
  from { transform: translateX(0); }
  to   { transform: translateX(-33.333%); }
}

/* ─── Nav ─── */
.ob-nav {
  position: sticky; top: 0; z-index: 50;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 48px; height: 60px;
  background: rgba(255,255,255,0.96);
  backdrop-filter: blur(12px);
  border-bottom: var(--border);
}
.ob-logo {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 20px; letter-spacing: 3px;
  color: var(--black); text-decoration: none;
  display: flex; align-items: center; gap: 8px;
}
.ob-logo-dot {
  width: 7px; height: 7px; border-radius: 50%;
  background: var(--red);
  animation: ob-pulse 2s ease-in-out infinite;
}
@keyframes ob-pulse { 0%,100%{opacity:1} 50%{opacity:0.25} }
.ob-nav-right { display: flex; align-items: center; gap: 14px; }
.ob-nav-hint { font-size: 12px; color: var(--muted); }
.ob-nav-login {
  font-size: 13px; font-weight: 700;
  color: var(--red); text-decoration: none;
  letter-spacing: 0.3px;
  transition: color 0.2s;
}
.ob-nav-login:hover { color: var(--black); }

/* ─── Step bar ─── */
.ob-stepbar {
  display: flex; align-items: center; justify-content: center;
  padding: 24px 48px;
  border-bottom: var(--border);
  background: var(--gray);
  gap: 0;
}
.ob-stepbar-item { display: flex; align-items: center; }
.ob-step-num {
  width: 30px; height: 30px;
  border: var(--border); background: var(--white);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Bebas Neue', sans-serif; font-size: 15px;
  color: var(--muted); transition: all 0.3s; flex-shrink: 0;
}
.ob-step-num svg { width: 13px; height: 13px; }
.ob-stepbar-item.is-active .ob-step-num  { background: var(--black); color: var(--white); border-color: var(--black); }
.ob-stepbar-item.is-done   .ob-step-num  { background: var(--red);   color: var(--white); border-color: var(--red); }
.ob-step-label {
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 1.5px;
  color: var(--muted); margin-left: 8px; white-space: nowrap;
}
.ob-stepbar-item.is-active .ob-step-label { color: var(--black); }
.ob-stepbar-item.is-done   .ob-step-label { color: var(--red); }
.ob-step-connector {
  width: 72px; height: 2px;
  background: var(--gray2); margin: 0 16px;
  transition: background 0.3s;
}
.ob-step-connector.is-filled { background: var(--red); }

/* ─── Stage ─── */
.ob-stage {
  max-width: 1080px;
  margin: 0 auto;
  padding: 52px 48px 80px;
}
.ob-stage--narrow { max-width: 720px; }

.ob-stage-head { margin-bottom: 40px; }
.ob-eyebrow {
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 3px;
  color: var(--red); margin-bottom: 8px;
}
.ob-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(48px, 6vw, 72px);
  line-height: 0.9; letter-spacing: 2px;
  margin-bottom: 14px;
}
.ob-title-outline {
  -webkit-text-stroke: 2.5px var(--black);
  color: transparent;
}
.ob-subtitle { font-size: 15px; color: var(--muted); line-height: 1.6; }

/* ─── Billing toggle ─── */
.ob-billing-toggle {
  display: flex; align-items: center; gap: 14px;
  margin-bottom: 32px;
}
.ob-billing-label { font-size: 14px; color: var(--muted); transition: color 0.2s; }
.ob-billing-label.is-active { color: var(--black); font-weight: 700; }
.ob-toggle-btn {
  width: 44px; height: 22px;
  background: var(--gray2); border: var(--border);
  cursor: pointer; position: relative; transition: background 0.2s;
}
.ob-toggle-btn.is-yearly { background: var(--black); }
.ob-toggle-thumb {
  position: absolute; top: 2px; left: 2px;
  width: 14px; height: 14px;
  background: var(--white);
  transition: transform 0.2s;
}
.ob-toggle-btn.is-yearly .ob-toggle-thumb { transform: translateX(22px); }
.ob-save-badge {
  background: var(--black); color: var(--white);
  font-size: 9px; font-weight: 800;
  letter-spacing: 1px; text-transform: uppercase;
  padding: 2px 7px; margin-left: 6px;
}

/* ─── Plans ─── */
.ob-plans {
  display: grid; grid-template-columns: repeat(3, 1fr);
  gap: 0; border: var(--border);
  margin-bottom: 24px;
}
.ob-plan {
  padding: 32px 28px;
  border-right: var(--border);
  cursor: pointer;
  position: relative;
  transition: background 0.2s;
  background: var(--white);
}
.ob-plan:last-child { border-right: none; }
.ob-plan:hover { background: var(--gray); }
.ob-plan.is-selected { background: var(--black); color: var(--white); }
.ob-plan.is-selected:hover { background: var(--black); }

.ob-plan-badge {
  position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
  background: var(--red); color: var(--white);
  font-size: 9px; font-weight: 800;
  letter-spacing: 2px; text-transform: uppercase;
  padding: 3px 14px; white-space: nowrap;
}
.ob-plan-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
.ob-plan-name {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 24px; letter-spacing: 1.5px;
}
.ob-plan-tagline { font-size: 11px; color: var(--muted); margin-top: 2px; }
.ob-plan.is-selected .ob-plan-tagline { color: rgba(255,255,255,0.45); }

.ob-plan-radio {
  width: 22px; height: 22px; border: var(--border);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; transition: all 0.2s;
  background: var(--white);
}
.ob-plan-radio.is-on { background: var(--red); border-color: var(--red); }
.ob-plan-radio svg { width: 11px; height: 11px; }

.ob-plan-price {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 44px; letter-spacing: 1px; line-height: 1;
  margin-bottom: 4px;
}
.ob-plan-period { font-size: 13px; color: var(--muted); font-family: 'DM Sans', sans-serif; }
.ob-plan.is-selected .ob-plan-period { color: rgba(255,255,255,0.5); }
.ob-plan-saving {
  font-size: 11px; font-weight: 700; color: var(--red);
  background: #fff0ef; padding: 2px 8px;
  display: inline-block; margin-bottom: 4px;
}
.ob-plan.is-selected .ob-plan-saving { background: rgba(232,24,12,0.2); }

.ob-plan-divider {
  height: 2px; background: var(--gray2);
  margin: 18px 0;
}
.ob-plan.is-selected .ob-plan-divider { background: rgba(255,255,255,0.1); }

.ob-plan-feats { list-style: none; display: flex; flex-direction: column; gap: 9px; }
.ob-plan-feats li {
  display: flex; align-items: center; gap: 9px;
  font-size: 12.5px; line-height: 1.4;
}
.ob-plan-feats li.is-off { color: var(--muted); }
.ob-plan.is-selected .ob-plan-feats li.is-off { color: rgba(255,255,255,0.3); }
.ob-feat-icon { font-size: 13px; flex-shrink: 0; font-weight: 700; }
.ob-plan-feats li:not(.is-off) .ob-feat-icon { color: var(--red); }
.ob-plan-feats li.is-off .ob-feat-icon { color: var(--gray2); }
.ob-plan.is-selected .ob-plan-feats li:not(.is-off) .ob-feat-icon { color: #ff6b63; }

/* Trial strip */
.ob-trial-strip {
  display: flex; align-items: center; gap: 9px;
  font-size: 13px; color: var(--muted);
  border: var(--border); padding: 13px 18px;
  margin-bottom: 32px; background: var(--gray);
}
.ob-trial-strip svg { width: 15px; height: 15px; flex-shrink: 0; color: var(--black); }

/* ─── Form Card ─── */
.ob-card {
  border: var(--border);
  margin-bottom: 28px;
}
.ob-section { padding: 28px 32px; display: flex; flex-direction: column; gap: 16px; }
.ob-card-divider { height: 2px; background: var(--black); }
.ob-section-label {
  display: flex; align-items: center; gap: 9px;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 16px; letter-spacing: 1.5px; color: var(--black);
}
.ob-section-icon {
  width: 28px; height: 28px; background: var(--black);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.ob-section-icon svg { width: 14px; height: 14px; color: var(--white); }

.ob-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.ob-field { display: flex; flex-direction: column; gap: 5px; }
.ob-field label {
  font-size: 10px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted);
}
.ob-req { color: var(--red); }

.ob-input {
  padding: 10px 13px;
  border: 1.5px solid var(--gray2);
  font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--black);
  background: var(--white); outline: none;
  transition: border-color 0.2s;
}
.ob-input:focus { border-color: var(--black); }
.ob-field.has-error .ob-input { border-color: var(--red); }
.ob-select { appearance: none; cursor: pointer; }
.ob-error-msg { font-size: 11px; color: var(--red); font-weight: 600; }

/* Password */
.ob-pass-wrap { position: relative; }
.ob-pass-wrap .ob-input { width: 100%; padding-right: 40px; }
.ob-pass-eye {
  position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer; color: var(--muted);
  display: flex; transition: color 0.2s;
}
.ob-pass-eye:hover { color: var(--black); }
.ob-pass-eye svg { width: 15px; height: 15px; }

.ob-strength { display: flex; align-items: center; gap: 8px; margin-top: 5px; }
.ob-strength-bars { display: flex; gap: 3px; }
.ob-strength-bar { width: 26px; height: 3px; background: var(--gray2); }
.ob-strength-bar.is-filled.lv-1 { background: var(--red); }
.ob-strength-bar.is-filled.lv-2 { background: #F59E0B; }
.ob-strength-bar.is-filled.lv-3 { background: #22C55E; }
.ob-strength-bar.is-filled.lv-4 { background: #059669; }
.ob-strength-label { font-size: 11px; font-weight: 700; }
.ob-strength-label.lv-1 { color: var(--red); }
.ob-strength-label.lv-2 { color: #F59E0B; }
.ob-strength-label.lv-3 { color: #22C55E; }
.ob-strength-label.lv-4 { color: #059669; }

/* Plan reminder */
.ob-plan-reminder {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 32px;
  border-top: var(--border);
  background: var(--gray);
}
.ob-pr-info { display: flex; align-items: center; gap: 12px; }
.ob-pr-dot {
  width: 10px; height: 10px; border-radius: 50%;
  background: var(--red); flex-shrink: 0;
}
.ob-pr-title { font-size: 13px; font-weight: 700; color: var(--black); }
.ob-pr-sub { font-size: 11px; color: var(--muted); margin-top: 2px; }
.ob-pr-change {
  font-size: 12px; font-weight: 700; color: var(--red);
  background: none; border: none; cursor: pointer;
  text-decoration: underline; text-underline-offset: 2px;
}

/* Terms */
.ob-terms {
  display: flex; align-items: flex-start; gap: 10px;
  cursor: pointer; padding: 16px 32px; padding-bottom: 0;
}
.ob-terms-check { display: none; }
.ob-terms-box {
  width: 16px; height: 16px; border: 1.5px solid var(--gray2);
  background: var(--white); flex-shrink: 0; position: relative; margin-top: 2px;
  transition: all 0.15s;
}
.ob-terms-check:checked + .ob-terms-box { background: var(--black); border-color: var(--black); }
.ob-terms-check:checked + .ob-terms-box::after {
  content: ''; position: absolute;
  left: 4px; top: 1px; width: 5px; height: 9px;
  border: 2px solid var(--white); border-top: none; border-left: none;
  transform: rotate(45deg);
}
.ob-terms.has-error .ob-terms-box { border-color: var(--red); }
.ob-terms-text { font-size: 13px; color: var(--muted); line-height: 1.5; }
.ob-link { color: var(--black); font-weight: 700; }

/* ─── Footer nav ─── */
.ob-foot {
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 0 24px;
}
.ob-btn-primary {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 14px 32px;
  background: var(--red); color: var(--white); border: var(--border);
  border-color: var(--red);
  font-family: 'Bebas Neue', sans-serif; font-size: 17px; letter-spacing: 1.5px;
  cursor: pointer; box-shadow: 4px 4px 0 var(--black);
  transition: all 0.2s;
}
.ob-btn-primary:hover:not(:disabled) {
  background: var(--black); border-color: var(--black);
  transform: translate(-2px, -2px); box-shadow: 6px 6px 0 var(--red);
}
.ob-btn-primary:disabled { opacity: 0.4; cursor: not-allowed; }
.ob-btn-primary svg { width: 16px; height: 16px; }

.ob-btn-back {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 20px;
  background: var(--white); color: var(--muted);
  border: 1.5px solid var(--gray2);
  font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600;
  cursor: pointer; transition: all 0.2s;
}
.ob-btn-back:hover { border-color: var(--black); color: var(--black); }
.ob-btn-back svg { width: 15px; height: 15px; }

/* Loading dots */
.ob-dots { display: inline-flex; gap: 4px; }
.ob-dots span { width: 5px; height: 5px; background: var(--white); border-radius: 50%; animation: ob-bounce 1s ease-in-out infinite; }
.ob-dots span:nth-child(2) { animation-delay: 0.15s; }
.ob-dots span:nth-child(3) { animation-delay: 0.3s; }
@keyframes ob-bounce { 0%,80%,100%{transform:scale(0.7);opacity:.5} 40%{transform:scale(1.1);opacity:1} }

/* ─── Success ─── */
.ob-success {
  border: var(--border);
  padding: 56px 48px;
  background: var(--white);
  text-align: center;
}
.ob-success-check {
  width: 72px; margin: 0 auto 28px;
}
.ob-success-check svg { width: 72px; height: 72px; }
.ob-check-draw {
  stroke-dasharray: 65;
  stroke-dashoffset: 65;
  animation: ob-draw 0.6s ease 0.3s forwards;
}
@keyframes ob-draw { to { stroke-dashoffset: 0; } }
.ob-success-sub {
  font-size: 15px; color: var(--muted); line-height: 1.7;
  margin: 16px 0 32px;
}

.ob-summary {
  display: grid; grid-template-columns: 1fr 1fr;
  border: var(--border); text-align: left;
  margin-bottom: 32px;
}
.ob-summary-item {
  padding: 16px 20px;
  border-right: var(--border);
  border-bottom: var(--border);
}
.ob-summary-item:nth-child(even) { border-right: none; }
.ob-summary-item:nth-child(3), .ob-summary-item:nth-child(4) { border-bottom: none; }
.ob-summary-label {
  font-size: 9px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 1.5px;
  color: var(--muted); display: block; margin-bottom: 4px;
}
.ob-summary-value { font-size: 14px; font-weight: 600; color: var(--black); }

.ob-next { text-align: left; margin-bottom: 32px; }
.ob-next-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 16px; letter-spacing: 1.5px;
  margin-bottom: 14px; color: var(--black);
}
.ob-next-list { display: flex; flex-direction: column; gap: 0; border: var(--border); }
.ob-next-item {
  display: flex; align-items: flex-start; gap: 16px;
  padding: 14px 18px; border-bottom: 1px solid var(--gray2);
}
.ob-next-item:last-child { border-bottom: none; }
.ob-next-num {
  width: 24px; height: 24px; background: var(--black);
  color: var(--white); font-family: 'Bebas Neue', sans-serif;
  font-size: 14px; display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.ob-next-label { font-size: 13px; font-weight: 700; color: var(--black); }
.ob-next-sub { font-size: 11px; color: var(--muted); margin-top: 2px; }

.ob-btn-enter {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 15px 40px;
  background: var(--black); color: var(--white);
  border: var(--border);
  font-family: 'Bebas Neue', sans-serif; font-size: 18px; letter-spacing: 2px;
  cursor: pointer; box-shadow: 4px 4px 0 var(--red);
  transition: all 0.2s; margin-bottom: 20px;
}
.ob-btn-enter:hover {
  background: var(--red); border-color: var(--red);
  transform: translate(-2px, -2px); box-shadow: 6px 6px 0 var(--black);
}
.ob-btn-enter svg { width: 16px; height: 16px; }
.ob-success-note { font-size: 12px; color: var(--muted); }

/* ─── Slide transition ─── */
.slide-enter-active, .slide-leave-active { transition: all 0.28s ease; }
.slide-enter-from { opacity: 0; transform: translateX(28px); }
.slide-leave-to   { opacity: 0; transform: translateX(-28px); }

/* ─── Responsive ─── */
@media (max-width: 900px) {
  .ob-plans { grid-template-columns: 1fr; border: none; gap: 2px; }
  .ob-plan { border-right: none; border: var(--border); }
  .ob-stage { padding: 36px 24px 60px; }
}
@media (max-width: 640px) {
  .ob-nav { padding: 0 18px; }
  .ob-stepbar { padding: 18px 16px; }
  .ob-step-connector { width: 32px; margin: 0 8px; }
  .ob-step-label { display: none; }
  .ob-form-row { grid-template-columns: 1fr; }
  .ob-summary { grid-template-columns: 1fr; }
  .ob-summary-item { border-right: none; }
  .ob-section { padding: 20px; }
  .ob-success { padding: 36px 24px; }
  .ob-plan-reminder { flex-direction: column; align-items: flex-start; gap: 10px; padding: 16px 20px; }
  .ob-terms { padding: 14px 20px 0; }
}
</style>



