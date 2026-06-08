<template>
  <MainLayout current-page="/billing" page-title="Suscripción y Pagos">
    <div class="billing-page">

      <!-- ── Banner de estado ── -->
      <div class="status-banner" :class="bannerClass">
        <div class="sb-left">
          <div class="sb-icon" v-html="bannerIcon"></div>
          <div>
            <p class="sb-title">{{ bannerTitle }}</p>
            <p class="sb-sub">{{ bannerSub }}</p>
          </div>
        </div>
        <div class="sb-right">
          <div v-if="isOnTrial" class="trial-countdown">
            <svg viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v5l3 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <span>{{ trialDaysLeft }} días restantes de prueba</span>
          </div>
          <button v-if="isOnTrial || isPastDue" class="btn-activate" @click="goToCheckout(currentPlan?.id)">
            {{ isPastDue ? 'Actualizar tarjeta' : 'Activar suscripción' }}
          </button>
        </div>
      </div>

      <!-- ── Plan actual + métricas ── -->
      <div class="billing-top-grid">

        <!-- Plan actual -->
        <div class="billing-card plan-current-card">
          <div class="bc-header">
            <h3 class="bc-title">Plan actual</h3>
            <span class="plan-badge" :class="`pb-${currentPlan?.slug}`">{{ currentPlan?.name }}</span>
          </div>

          <div class="plan-price-big">
            <span class="ppb-amount">${{ formatNum(currentPlan?.price) }}</span>
            <span class="ppb-period">MXN/mes</span>
          </div>

          <div class="plan-usage">
            <div class="pu-row">
              <span class="pu-label">Empleados</span>
              <span class="pu-val">{{ usageEmployees }} / {{ currentPlan?.max_employees === -1 ? '∞' : currentPlan?.max_employees }}</span>
            </div>
            <div class="pu-bar">
              <div class="pu-fill" :style="{ width: employeeUsagePct + '%' }" :class="{ 'pu-warn': employeeUsagePct > 80 }"></div>
            </div>

            <div class="pu-row" style="margin-top:12px">
              <span class="pu-label">Reportes este mes</span>
              <span class="pu-val">{{ usageReports }} / {{ currentPlan?.max_reports_per_month === -1 ? '∞' : currentPlan?.max_reports_per_month }}</span>
            </div>
            <div v-if="currentPlan?.max_reports_per_month !== -1" class="pu-bar">
              <div class="pu-fill" :style="{ width: reportUsagePct + '%' }" :class="{ 'pu-warn': reportUsagePct > 80 }"></div>
            </div>
          </div>

          <div class="plan-renewal">
            <svg viewBox="0 0 16 16" fill="none"><path d="M3 8A5 5 0 1 0 8 3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M3 5v3h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span>{{ renewalText }}</span>
          </div>

          <div class="plan-actions">
            <button class="btn-portal" @click="openPortal">
              <svg viewBox="0 0 16 16" fill="none"><rect x="2" y="3" width="12" height="9" rx="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M5 12v1M11 12v1M5 7h6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
              Gestionar tarjeta
            </button>
            <button v-if="!isCancelled" class="btn-cancel" @click="showCancelModal = true">Cancelar plan</button>
          </div>
        </div>

        <!-- Resumen de próximo cobro -->
        <div class="billing-card next-payment-card">
          <h3 class="bc-title">Próximo cobro</h3>
          <div class="next-pay-amount">${{ formatNum(currentPlan?.price) }} <span>MXN</span></div>
          <div class="next-pay-date">{{ nextPaymentDate }}</div>

          <div class="next-pay-breakdown">
            <div class="npb-row">
              <span>Plan {{ currentPlan?.name }}</span>
              <span>${{ formatNum(currentPlan?.price) }}</span>
            </div>
            <div class="npb-row muted">
              <span>IVA incluido</span>
              <span>—</span>
            </div>
            <div class="npb-divider"></div>
            <div class="npb-row total">
              <span>Total</span>
              <span>${{ formatNum(currentPlan?.price) }} MXN</span>
            </div>
          </div>

          <div class="payment-method-row" v-if="paymentMethod">
            <svg viewBox="0 0 20 20" fill="none"><rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M2 8h16" stroke="currentColor" stroke-width="1.4"/></svg>
            <span>•••• •••• •••• {{ paymentMethod.last4 }}</span>
            <span class="pm-brand">{{ paymentMethod.brand }}</span>
          </div>
        </div>

      </div>

      <!-- ── Cambiar de plan ── -->
      <div class="billing-card change-plan-card">
        <div class="cpc-header">
          <div>
            <h3 class="bc-title">Cambiar de plan</h3>
            <p class="bc-sub">El cambio aplica inmediatamente. Se prorratea la diferencia.</p>
          </div>
        </div>

        <div class="plans-row">
          <div v-for="plan in plans" :key="plan.id"
            class="plan-option" :class="{
              current: plan.id === currentPlan?.id,
              selected: selectedPlan === plan.id,
            }"
            @click="plan.id !== currentPlan?.id ? selectedPlan = plan.id : null">

            <div v-if="plan.id === currentPlan?.id" class="po-current-tag">Plan actual</div>

            <div class="po-head">
              <span class="po-name">{{ plan.name }}</span>
              <span class="po-price">${{ formatNum(plan.price) }}<span class="po-period">/mes</span></span>
            </div>

            <div class="po-limit">Hasta {{ plan.max_employees === -1 ? '∞' : plan.max_employees }} empleados</div>

            <ul class="po-features">
              <li v-for="f in plan.features.slice(0,3)" :key="f">✓ {{ f }}</li>
            </ul>

            <div v-if="plan.id !== currentPlan?.id" class="po-radio">
              <div class="po-dot" :class="{ filled: selectedPlan === plan.id }"></div>
              <span>{{ selectedPlan === plan.id ? 'Seleccionado' : 'Seleccionar' }}</span>
            </div>
          </div>
        </div>

        <div v-if="selectedPlan && selectedPlan !== currentPlan?.id" class="plan-change-confirm">
          <div class="pcc-info">
            <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.2"/><path d="M8 5v4M8 10.5v.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
            <span>
              Cambiarás de <strong>{{ currentPlan?.name }}</strong> a <strong>{{ selectedPlanData?.name }}</strong>.
              {{ isUpgrade ? 'Se cobrará la diferencia prorrata de inmediato.' : 'El cambio aplica al siguiente ciclo.' }}
            </span>
          </div>
          <button class="btn-change-plan" @click="confirmChangePlan" :disabled="isChanging">
            <span v-if="!isChanging">Confirmar cambio</span>
            <span v-else class="dots"><span></span><span></span><span></span></span>
          </button>
        </div>
      </div>

      <!-- ── Historial de facturas ── -->
      <div class="billing-card invoices-card">
        <h3 class="bc-title" style="margin-bottom:16px">Historial de pagos</h3>

        <div v-if="invoices.length === 0" class="invoices-empty">
          No hay facturas aún. Aparecerán aquí al activar tu suscripción.
        </div>

        <table v-else class="invoices-table">
          <thead>
            <tr>
              <th>Período</th>
              <th>Número</th>
              <th>Monto</th>
              <th>Estado</th>
              <th>PDF</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="inv in invoices" :key="inv.id">
              <td class="inv-period">{{ inv.period }}</td>
              <td class="inv-num">{{ inv.number }}</td>
              <td class="inv-amount">${{ formatNum(inv.amount) }} MXN</td>
              <td>
                <span class="inv-status" :class="`is-${inv.status}`">
                  {{ inv.status === 'paid' ? 'Pagado' : inv.status === 'open' ? 'Pendiente' : inv.status }}
                </span>
              </td>
              <td>
                <a v-if="inv.pdf_url" :href="inv.pdf_url" target="_blank" class="inv-pdf">
                  <svg viewBox="0 0 14 14" fill="none"><path d="M3 1h6l3 3v9H3V1z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/><path d="M9 1v3h3" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/><path d="M5 8h4M5 6h2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                  Descargar
                </a>
                <span v-else class="inv-pdf-na">—</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>

    <!-- ── Modal cancelar ── -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showCancelModal" class="modal-overlay" @mousedown.self="showCancelModal = false">
          <div class="modal-box cancel-modal">
            <div class="cancel-icon">
              <svg viewBox="0 0 32 32" fill="none"><circle cx="16" cy="16" r="14" stroke="#EF4444" stroke-width="2"/><path d="M16 9v8M16 20v2" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/></svg>
            </div>
            <h2 class="cancel-title">¿Cancelar suscripción?</h2>
            <p class="cancel-body">
              Seguirás teniendo acceso hasta el <strong>{{ nextPaymentDate }}</strong>.
              Después de esa fecha, tu cuenta quedará suspendida y no podrás acceder al sistema.
            </p>
            <div class="cancel-actions">
              <button class="btn-ghost" @click="showCancelModal = false">Mantener plan</button>
              <button class="btn-confirm-cancel" @click="confirmCancel" :disabled="isCancelling">
                <span v-if="!isCancelling">Sí, cancelar</span>
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
        <div v-if="toast.show" class="toast" :class="toast.type">{{ toast.message }}</div>
      </Transition>
    </Teleport>

  </MainLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import MainLayout from '../Layouts/MainLayout.vue'
import axios from 'axios'

const props = defineProps({
  company:      { type: Object, default: null },
  subscription: { type: Object, default: null },
  plan:         { type: Object, default: null },
  plans:        { type: Array,  default: () => [] },
  invoices:     { type: Array,  default: () => [] },
})

const selectedPlan   = ref(null)
const showCancelModal = ref(false)
const isChanging     = ref(false)
const isCancelling   = ref(false)
const toast          = reactive({ show: false, message: '', type: 'success' })

// Demo data si no hay props reales
const currentPlan = computed(() => props.plan ?? {
  id: 2, name: 'Profesional', slug: 'profesional',
  price: 1299, max_employees: 100, max_reports_per_month: -1,
  features: ['Hasta 100 empleados', 'Reportes ilimitados', 'Dashboard avanzado'],
})

const plans = computed(() => props.plans.length ? props.plans : [
  { id:1, name:'Básico',      slug:'basico',      price:499,  max_employees:25,  max_reports_per_month:3,  features:['Hasta 25 empleados','3 reportes PDF/mes','Soporte email'] },
  { id:2, name:'Profesional', slug:'profesional', price:1299, max_employees:100, max_reports_per_month:-1, features:['Hasta 100 empleados','Reportes ilimitados','Soporte prioritario'] },
  { id:3, name:'Empresarial', slug:'empresarial', price:2999, max_employees:500, max_reports_per_month:-1, features:['Hasta 500 empleados','API REST','Gestor dedicado'] },
])

const invoices = computed(() => props.invoices.length ? props.invoices : [
  { id:'in_1', number:'NOM-0024', amount:1299, status:'paid', date:'01/05/2025', period:'May 2025', pdf_url:'#' },
  { id:'in_2', number:'NOM-0023', amount:1299, status:'paid', date:'01/04/2025', period:'Abr 2025', pdf_url:'#' },
  { id:'in_3', number:'NOM-0022', amount:1299, status:'paid', date:'01/03/2025', period:'Mar 2025', pdf_url:'#' },
])

const usageEmployees  = ref(42)
const usageReports    = ref(0)
const paymentMethod   = ref({ last4: '4242', brand: 'Visa' })

const isOnTrial   = computed(() => props.subscription?.status === 'trial' || !props.subscription)
const isPastDue   = computed(() => props.subscription?.status === 'past_due')
const isCancelled = computed(() => props.subscription?.status === 'cancelled')

const trialDaysLeft = computed(() => {
  if (!props.subscription?.trial_ends_at) return 3
  const diff = new Date(props.subscription.trial_ends_at) - new Date()
  return Math.max(0, Math.ceil(diff / (1000 * 60 * 60 * 24)))
})

const employeeUsagePct = computed(() => {
  const max = currentPlan.value.max_employees
  if (max === -1) return 20
  return Math.min(100, Math.round(usageEmployees.value / max * 100))
})

const reportUsagePct = computed(() => {
  const max = currentPlan.value.max_reports_per_month
  if (max === -1) return 0
  return Math.min(100, Math.round(usageReports.value / max * 100))
})

const nextPaymentDate = computed(() => {
  const d = new Date()
  d.setMonth(d.getMonth() + 1)
  return d.toLocaleDateString('es-MX', { day: '2-digit', month: 'long', year: 'numeric' })
})

const renewalText = computed(() => {
  if (isOnTrial.value) return `Prueba gratuita · termina en ${trialDaysLeft.value} días`
  if (isCancelled.value) return 'Suscripción cancelada · acceso hasta ' + nextPaymentDate.value
  return `Próxima renovación: ${nextPaymentDate.value}`
})

const selectedPlanData = computed(() => plans.value.find(p => p.id === selectedPlan.value))
const isUpgrade        = computed(() => (selectedPlanData.value?.price ?? 0) > (currentPlan.value?.price ?? 0))

// Banner dinámico según estado
const bannerClass = computed(() => ({
  'banner-trial':    isOnTrial.value,
  'banner-active':   !isOnTrial.value && !isPastDue.value && !isCancelled.value,
  'banner-pastdue':  isPastDue.value,
  'banner-cancelled':isCancelled.value,
}))
const bannerTitle = computed(() => {
  if (isOnTrial.value)   return `Período de prueba — ${trialDaysLeft.value} días restantes`
  if (isPastDue.value)   return 'Pago fallido — Actualiza tu método de pago'
  if (isCancelled.value) return 'Suscripción cancelada'
  return `Suscripción activa — Plan ${currentPlan.value?.name}`
})
const bannerSub = computed(() => {
  if (isOnTrial.value)   return 'Activa tu suscripción antes de que termine tu prueba gratuita.'
  if (isPastDue.value)   return 'Tu acceso se suspenderá en 3 días si no actualizas tu tarjeta.'
  if (isCancelled.value) return 'Tu acceso termina al final del período de facturación.'
  return 'Tu suscripción se renueva automáticamente cada mes.'
})
const bannerIcon = computed(() => {
  if (isPastDue.value || isCancelled.value) {
    return `<svg viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v5M10 13h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>`
  }
  return `<svg viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M6.5 10l2.5 2.5 4-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`
})

// ── Métodos ──
function formatNum(n) { return Number(n ?? 0).toLocaleString('es-MX') }

async function goToCheckout(planId) {
  try {
    const { data } = await axios.post('/api/billing/checkout', { plan_id: planId ?? currentPlan.value.id })
    window.location.href = data.checkout_url
  } catch { showToast('Error al iniciar el pago. Intenta de nuevo.', 'error') }
}

function openPortal() {
  window.location.href = '/billing/portal'
}

async function confirmChangePlan() {
  isChanging.value = true
  try {
    await axios.post('/api/billing/change-plan', { plan_id: selectedPlan.value })
    showToast(`Plan actualizado a ${selectedPlanData.value?.name}`)
    selectedPlan.value = null
  } catch { showToast('Error al cambiar el plan', 'error') }
  finally { isChanging.value = false }
}

async function confirmCancel() {
  isCancelling.value = true
  try {
    await axios.post('/api/billing/cancel')
    showCancelModal.value = false
    showToast('Suscripción cancelada. Tienes acceso hasta ' + nextPaymentDate.value)
  } catch { showToast('Error al cancelar', 'error') }
  finally { isCancelling.value = false }
}

function showToast(message, type = 'success') {
  toast.message = message; toast.type = type; toast.show = true
  setTimeout(() => toast.show = false, 3500)
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700;800&display=swap');
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
.billing-page { display: flex; flex-direction: column; gap: 20px; font-family: 'DM Sans', sans-serif; }

/* ── Status banner ── */
.status-banner {
  border-radius: 14px; padding: 18px 24px;
  display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
.banner-trial    { background: linear-gradient(130deg, #0C4A6E, #0369A1); color: white; }
.banner-active   { background: linear-gradient(130deg, #064E3B, #065F46); color: white; }
.banner-pastdue  { background: linear-gradient(130deg, #7C2D12, #B91C1C); color: white; }
.banner-cancelled{ background: linear-gradient(130deg, #1F2937, #374151); color: white; }
.sb-left  { display: flex; align-items: center; gap: 14px; }
.sb-icon  { width: 36px; height: 36px; flex-shrink: 0; opacity: 0.9; }
.sb-icon  :deep(svg) { width: 36px; height: 36px; }
.sb-title { font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 800; margin-bottom: 2px; }
.sb-sub   { font-size: 12px; opacity: 0.75; }
.sb-right { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
.trial-countdown { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; opacity: 0.85; }
.trial-countdown svg { width: 15px; height: 15px; }
.btn-activate {
  padding: 9px 20px; background: white; color: #0C4A6E;
  border: none; border-radius: 9px;
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: all 0.2s; white-space: nowrap;
}
.btn-activate:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }

/* ── Cards ── */
.billing-top-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.billing-card {
  background: white; border: 1px solid #E2E8F0; border-radius: 16px; padding: 24px;
}
.bc-title { font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 800; color: #0C4A6E; }
.bc-sub   { font-size: 12px; color: #94A3B8; margin-top: 3px; }
.bc-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }

/* Plan badge */
.plan-badge { font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 100px; }
.pb-basico      { background: #EEF2FF; color: #4338CA; }
.pb-profesional { background: #F0F9FF; color: #0369A1; }
.pb-empresarial { background: #ECFDF5; color: #047857; }

.plan-price-big { margin-bottom: 20px; }
.ppb-amount { font-family: 'Syne', sans-serif; font-size: 40px; font-weight: 800; color: #0C4A6E; line-height: 1; }
.ppb-period { font-size: 14px; color: #94A3B8; margin-left: 4px; }

/* Usage bars */
.plan-usage { margin-bottom: 20px; }
.pu-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; }
.pu-label { color: #64748B; }
.pu-val   { font-weight: 700; color: #1E293B; }
.pu-bar   { height: 6px; background: #F1F5F9; border-radius: 100px; overflow: hidden; }
.pu-fill  { height: 100%; background: #0EA5E9; border-radius: 100px; transition: width 0.8s ease; }
.pu-fill.pu-warn { background: #F59E0B; }

.plan-renewal {
  display: flex; align-items: center; gap: 6px;
  font-size: 12px; color: #64748B;
  padding: 10px 0; border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9;
  margin-bottom: 16px;
}
.plan-renewal svg { width: 14px; height: 14px; color: #0EA5E9; }
.plan-actions { display: flex; gap: 10px; }
.btn-portal {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 16px; background: #F0F9FF; color: #0369A1;
  border: none; border-radius: 9px;
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: all 0.2s; flex: 1; justify-content: center;
}
.btn-portal:hover { background: #BAE6FD; }
.btn-portal svg { width: 14px; height: 14px; }
.btn-cancel {
  padding: 9px 16px; background: none; color: #94A3B8;
  border: none; font-family: 'DM Sans', sans-serif; font-size: 12px;
  cursor: pointer; transition: color 0.2s; text-decoration: underline;
}
.btn-cancel:hover { color: #EF4444; }

/* Next payment */
.next-payment-card {}
.next-pay-amount { font-family: 'Syne', sans-serif; font-size: 36px; font-weight: 800; color: #0C4A6E; margin: 16px 0 4px; }
.next-pay-amount span { font-size: 14px; color: #94A3B8; font-family: 'DM Sans', sans-serif; font-weight: 400; margin-left: 4px; }
.next-pay-date { font-size: 13px; color: #64748B; margin-bottom: 20px; }
.next-pay-breakdown { display: flex; flex-direction: column; gap: 8px; padding: 16px; background: #F8FAFC; border-radius: 10px; margin-bottom: 16px; }
.npb-row { display: flex; justify-content: space-between; font-size: 13px; color: #374151; }
.npb-row.muted { color: #94A3B8; font-size: 12px; }
.npb-row.total { font-weight: 700; color: #0C4A6E; }
.npb-divider { height: 1px; background: #E2E8F0; }
.payment-method-row { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #374151; padding: 10px 12px; background: #F8FAFC; border-radius: 9px; }
.payment-method-row svg { width: 18px; height: 18px; color: #64748B; }
.pm-brand { margin-left: auto; font-size: 11px; font-weight: 700; color: #64748B; background: white; padding: 2px 8px; border-radius: 5px; border: 1px solid #E2E8F0; }

/* Change plan */
.change-plan-card {}
.cpc-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
.plans-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 16px; }
.plan-option {
  border: 2px solid #E2E8F0; border-radius: 13px; padding: 16px;
  cursor: pointer; transition: all 0.2s; position: relative;
}
.plan-option:hover:not(.current) { border-color: #BAE6FD; background: #F0F9FF; }
.plan-option.selected { border-color: #0EA5E9; background: #F0F9FF; }
.plan-option.current  { background: #F8FAFC; cursor: default; opacity: 0.7; }
.po-current-tag {
  position: absolute; top: -10px; left: 12px;
  background: #0C4A6E; color: white;
  font-size: 9px; font-weight: 700; letter-spacing: 1px;
  text-transform: uppercase; padding: 3px 9px; border-radius: 100px;
}
.po-head { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 4px; }
.po-name  { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 800; color: #0C4A6E; }
.po-price { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; color: #0369A1; }
.po-period{ font-size: 11px; color: #94A3B8; font-family: 'DM Sans', sans-serif; font-weight: 400; }
.po-limit { font-size: 10px; color: #94A3B8; margin-bottom: 10px; }
.po-features { list-style: none; display: flex; flex-direction: column; gap: 4px; margin-bottom: 12px; }
.po-features li { font-size: 11px; color: #64748B; }
.po-radio { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #64748B; }
.po-dot { width: 14px; height: 14px; border-radius: 50%; border: 2px solid #CBD5E1; transition: all 0.18s; }
.po-dot.filled { background: #0EA5E9; border-color: #0EA5E9; }

.plan-change-confirm {
  display: flex; align-items: center; justify-content: space-between; gap: 12px;
  background: #F0F9FF; border: 1px solid #BAE6FD; border-radius: 11px; padding: 14px 16px;
}
.pcc-info { display: flex; align-items: flex-start; gap: 8px; font-size: 13px; color: #0C4A6E; flex: 1; }
.pcc-info svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; color: #0369A1; }
.btn-change-plan {
  padding: 10px 20px; background: linear-gradient(135deg, #0369A1, #0EA5E9);
  color: white; border: none; border-radius: 9px;
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: all 0.2s; white-space: nowrap; flex-shrink: 0;
}
.btn-change-plan:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(14,165,233,0.3); }
.btn-change-plan:disabled { opacity: 0.6; cursor: not-allowed; }

/* Invoices */
.invoices-empty { text-align: center; padding: 32px; font-size: 13px; color: #94A3B8; }
.invoices-table { width: 100%; border-collapse: collapse; }
.invoices-table th {
  padding: 8px 12px; text-align: left;
  font-size: 10px; font-weight: 700; color: #94A3B8;
  text-transform: uppercase; letter-spacing: 0.8px;
  border-bottom: 1px solid #F1F5F9; background: #F8FAFC;
}
.invoices-table td { padding: 12px 12px; font-size: 13px; border-bottom: 1px solid #F8FAFC; }
.invoices-table tr:last-child td { border-bottom: none; }
.inv-period { font-weight: 600; color: #1E293B; }
.inv-num    { color: #64748B; font-size: 12px; }
.inv-amount { font-weight: 700; color: #0C4A6E; }
.inv-status { font-size: 10px; font-weight: 700; padding: 3px 9px; border-radius: 100px; }
.is-paid    { background: #F0FDF4; color: #16A34A; }
.is-open    { background: #FFFBEB; color: #D97706; }
.is-void    { background: #F1F5F9; color: #64748B; }
.inv-pdf {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 12px; color: #0EA5E9; text-decoration: none; font-weight: 600;
  transition: color 0.2s;
}
.inv-pdf:hover { color: #0369A1; }
.inv-pdf svg { width: 13px; height: 13px; }
.inv-pdf-na { color: #CBD5E1; font-size: 12px; }

/* ── Cancel modal ── */
.modal-overlay {
  position: fixed; inset: 0; z-index: 999;
  background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
  display: flex; align-items: center; justify-content: center; padding: 20px;
}
.modal-box {
  background: white; border-radius: 18px; padding: 32px;
  width: 100%; max-width: 440px;
  box-shadow: 0 24px 60px rgba(0,0,0,0.15);
  text-align: center;
}
.cancel-icon { margin-bottom: 16px; }
.cancel-icon svg { width: 48px; height: 48px; }
.cancel-title { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 800; color: #0C4A6E; margin-bottom: 12px; }
.cancel-body  { font-size: 14px; color: #64748B; line-height: 1.6; margin-bottom: 24px; }
.cancel-actions { display: flex; gap: 10px; justify-content: center; }
.btn-ghost {
  padding: 11px 20px; background: white; color: #374151;
  border: 1.5px solid #E2E8F0; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600; cursor: pointer;
  transition: all 0.2s;
}
.btn-ghost:hover { border-color: #CBD5E1; }
.btn-confirm-cancel {
  padding: 11px 20px; background: #EF4444; color: white;
  border: none; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 700;
  cursor: pointer; transition: all 0.2s;
}
.btn-confirm-cancel:hover:not(:disabled) { background: #DC2626; }
.btn-confirm-cancel:disabled { opacity: 0.6; cursor: not-allowed; }

/* dots */
.dots { display: inline-flex; gap: 4px; }
.dots span { width: 5px; height: 5px; background: currentColor; border-radius: 50%; animation: bounce 1s ease-in-out infinite; }
.dots span:nth-child(2) { animation-delay: 0.15s; }
.dots span:nth-child(3) { animation-delay: 0.3s; }
@keyframes bounce { 0%,80%,100%{transform:scale(0.8);opacity:.5} 40%{transform:scale(1.1);opacity:1} }

/* Toast */
.toast {
  position: fixed; bottom: 24px; right: 24px; z-index: 9999;
  padding: 13px 20px; border-radius: 11px;
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;
  box-shadow: 0 8px 24px rgba(0,0,0,0.15); min-width: 240px;
}
.toast.success { background: #0C4A6E; color: white; }
.toast.error   { background: #DC2626; color: white; }
.toast-enter-active, .toast-leave-active { transition: all 0.3s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(12px); }
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .modal-box, .modal-leave-to .modal-box { transform: scale(0.95) translateY(8px); }

@media (max-width: 900px) {
  .billing-top-grid { grid-template-columns: 1fr; }
  .plans-row { grid-template-columns: 1fr; }
}
</style>
