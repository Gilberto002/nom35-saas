<template>
  <div class="login-root">

    <!-- Ticker -->
    <div class="login-ticker">
      <div class="login-ticker-track">
        <span v-for="i in 3" :key="i" class="login-ticker-inner">
          <span class="login-ticker-item">NOM-035-STPS-2018</span>
          <span class="login-ticker-item">Plataforma certificada</span>
          <span class="login-ticker-item">Cumplimiento normativo</span>
          <span class="login-ticker-item">Bienestar laboral</span>
          <span class="login-ticker-item">+500 empresas activas</span>
        </span>
      </div>
    </div>

    <div class="login-layout">

      <!-- Panel izquierdo -->
      <div class="login-brand">
        <a href="/" class="login-logo">
          <span class="login-logo-dot"></span>
          NOM-035 SaaS
        </a>

        <div class="login-brand-body">
          <p class="login-eyebrow">Acceso al sistema</p>
          <h1 class="login-headline">
            BIENVENIDO<br>
            <span class="login-headline-outline">DE VUELTA</span>
          </h1>
          <p class="login-brand-desc">
            Gestiona el cumplimiento de la NOM-035-STPS-2018 de tu empresa desde un solo lugar.
          </p>

          <div class="login-stats">
            <div class="login-stat">
              <span class="login-stat-num">3</span>
              <span class="login-stat-label">Guías NOM-035</span>
            </div>
            <div class="login-stat-div"></div>
            <div class="login-stat">
              <span class="login-stat-num">500+</span>
              <span class="login-stat-label">Empresas activas</span>
            </div>
            <div class="login-stat-div"></div>
            <div class="login-stat">
              <span class="login-stat-num">100%</span>
              <span class="login-stat-label">Cumplimiento STPS</span>
            </div>
          </div>
        </div>

        <div class="login-brand-foot">
          © 2025 NOM-035 SaaS · Todos los derechos reservados
        </div>
      </div>

      <!-- Panel derecho — formulario -->
      <div class="login-form-panel">
        <div class="login-form-wrap">

          <div class="login-form-head">
            <div class="login-form-icon">
              <svg viewBox="0 0 24 24" fill="none">
                <rect x="3" y="11" width="18" height="11" rx="1.5" stroke="currentColor" stroke-width="1.6"/>
                <path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
              </svg>
            </div>
            <p class="login-eyebrow" style="margin-bottom:6px">Panel de administración</p>
            <h2 class="login-form-title">Iniciar sesión</h2>
            <p class="login-form-sub">Ingresa tus credenciales para continuar</p>
          </div>

          <form @submit.prevent="handleLogin" class="login-form" novalidate>

            <div class="login-field" :class="{ 'has-error': errors.email }">
              <label>Correo electrónico</label>
              <div class="login-input-wrap">
                <svg class="login-input-icon" viewBox="0 0 18 18" fill="none">
                  <path d="M2 5h14v9a1 1 0 01-1 1H3a1 1 0 01-1-1V5z" stroke="currentColor" stroke-width="1.3"/>
                  <path d="M2 5l7 6 7-6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                <input
                  v-model="form.email"
                  type="email"
                  placeholder="empresa@correo.com"
                  class="login-input"
                  autocomplete="email"
                />
              </div>
              <span v-if="errors.email" class="login-error">{{ errors.email }}</span>
            </div>

            <div class="login-field" :class="{ 'has-error': errors.password }">
              <div class="login-field-top">
                <label>Contraseña</label>
                <a href="#" class="login-forgot">¿Olvidaste tu contraseña?</a>
              </div>
              <div class="login-input-wrap">
                <svg class="login-input-icon" viewBox="0 0 18 18" fill="none">
                  <rect x="3" y="8" width="12" height="8" rx="1.2" stroke="currentColor" stroke-width="1.3"/>
                  <path d="M6 8V5.5a3 3 0 016 0V8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                <input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="••••••••"
                  class="login-input"
                  autocomplete="current-password"
                />
                <button type="button" class="login-eye" @click="showPassword = !showPassword">
                  <svg v-if="!showPassword" viewBox="0 0 18 18" fill="none">
                    <path d="M2 9s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="currentColor" stroke-width="1.3"/>
                    <circle cx="9" cy="9" r="2" stroke="currentColor" stroke-width="1.3"/>
                  </svg>
                  <svg v-else viewBox="0 0 18 18" fill="none">
                    <path d="M2 2l14 14M8 7.2A2 2 0 0111 10M5 5.3C3.3 6.4 2 8.3 2 9s3 5 7 5c1.3 0 2.5-.3 3.5-.9" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                    <path d="M9.5 4C13 4.5 16 7.2 16 9c0 .6-.4 1.4-1.2 2.2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                  </svg>
                </button>
              </div>
              <span v-if="errors.password" class="login-error">{{ errors.password }}</span>
            </div>

            <label class="login-remember">
              <input type="checkbox" v-model="form.remember" class="login-remember-check"/>
              <span class="login-remember-box"></span>
              <span>Mantener sesión iniciada</span>
            </label>

            <button type="submit" class="login-btn" :disabled="isLoading">
              <span v-if="!isLoading">Ingresar al sistema</span>
              <span v-else class="login-dots"><span></span><span></span><span></span></span>
              <svg v-if="!isLoading" viewBox="0 0 16 16" fill="none">
                <path d="M3 8h10M8 3l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>

            <div v-if="errorMessage" class="login-alert">
              <svg viewBox="0 0 16 16" fill="none">
                <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/>
                <path d="M8 5v4M8 11h.01" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
              </svg>
              {{ errorMessage }}
            </div>
          </form>

          <div class="login-form-bottom">
            ¿Tu empresa aún no tiene cuenta?
            <a href="/register" class="login-register-link">Comenzar 30 días gratis →</a>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

const form = reactive({ email: '', password: '', remember: false })
const errors = reactive({ email: '', password: '' })
const errorMessage = ref('')
const isLoading = ref(false)
const showPassword = ref(false)

async function handleLogin() {
  errors.email = ''
  errors.password = ''
  errorMessage.value = ''
  form.email = form.email.trim().toLowerCase()
  if (!form.email)    { errors.email    = 'El correo es requerido'; return }
  if (!form.password) { errors.password = 'La contraseña es requerida'; return }
  isLoading.value = true
  try {
    const res = await axios.post('/login', {
  email:    form.email,
  password: form.password,
  remember: form.remember,
})
// Guardar token para requests futuros
axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`
localStorage.setItem('token', res.data.token)
window.location.href = '/dashboard'
  } catch (err) {
    if (err.response?.status === 422) {
      const e = err.response.data.errors || {}
      errors.email    = e.email?.[0]    || ''
      errors.password = e.password?.[0] || ''
    } else {
      errorMessage.value = 'Credenciales incorrectas. Verifica tus datos.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600;700&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.login-root {
  min-height: 100vh;
  background: #F2F2F0;
  font-family: 'DM Sans', sans-serif;
  color: #0A0A0A;
  display: flex;
  flex-direction: column;
  overflow-x: hidden;
}

/* ── Ticker ── */
.login-ticker {
  background: #E8180C;
  color: #fff;
  padding: 7px 0;
  overflow: hidden;
  flex-shrink: 0;
}
.login-ticker-track {
  display: flex;
  white-space: nowrap;
  animation: lticker 22s linear infinite;
}
.login-ticker-inner { display: flex; }
.login-ticker-item {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 12px;
  letter-spacing: 2.5px;
  padding: 0 28px;
}
.login-ticker-item::after { content: ' ✦'; opacity: 0.6; }
@keyframes lticker {
  from { transform: translateX(0); }
  to   { transform: translateX(-33.333%); }
}

/* ── Layout ── */
.login-layout {
  flex: 1;
  display: grid;
  grid-template-columns: 1fr 480px;
  min-height: calc(100vh - 33px);
}

/* ── Brand panel ── */
.login-brand {
  background: #0A0A0A;
  padding: 52px 64px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  position: relative;
  overflow: hidden;
}
.login-brand::before {
  content: 'NOM\A035';
  white-space: pre;
  position: absolute;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 220px;
  line-height: 0.85;
  color: rgba(255,255,255,0.03);
  right: -20px;
  bottom: 40px;
  letter-spacing: 4px;
  pointer-events: none;
}

.login-logo {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 20px;
  letter-spacing: 3px;
  color: #fff;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 8px;
}
.login-logo-dot {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: #E8180C;
  animation: lpulse 2s ease-in-out infinite;
}
@keyframes lpulse { 0%,100%{opacity:1} 50%{opacity:0.2} }

.login-brand-body { position: relative; z-index: 1; }

.login-eyebrow {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 3px;
  color: #E8180C;
  margin-bottom: 12px;
  display: block;
}

.login-headline {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(52px, 5vw, 80px);
  line-height: 0.9;
  letter-spacing: 2px;
  color: #fff;
  margin-bottom: 20px;
}
.login-headline-outline {
  -webkit-text-stroke: 2.5px #fff;
  color: transparent;
}

.login-brand-desc {
  font-size: 15px;
  color: rgba(255,255,255,0.5);
  line-height: 1.7;
  max-width: 360px;
  margin-bottom: 48px;
}

.login-stats {
  display: flex;
  align-items: center;
  gap: 28px;
}
.login-stat { text-align: center; }
.login-stat-num {
  display: block;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 32px;
  color: #E8180C;
  line-height: 1;
}
.login-stat-label {
  display: block;
  font-size: 10px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: rgba(255,255,255,0.35);
  margin-top: 4px;
}
.login-stat-div {
  width: 1px;
  height: 40px;
  background: rgba(255,255,255,0.1);
}

.login-brand-foot {
  font-size: 11px;
  color: rgba(255,255,255,0.2);
  position: relative; z-index: 1;
}

/* ── Form panel ── */
.login-form-panel {
  background: #fff;
  border-left: 2px solid #0A0A0A;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 52px 48px;
}

.login-form-wrap { width: 100%; max-width: 360px; }

.login-form-head { margin-bottom: 36px; }
.login-form-icon {
  width: 44px; height: 44px;
  background: #0A0A0A;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 20px;
}
.login-form-icon svg { width: 20px; height: 20px; color: #fff; stroke: #fff; }

.login-form-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 32px;
  letter-spacing: 2px;
  color: #0A0A0A;
  margin-bottom: 4px;
}
.login-form-sub { font-size: 13px; color: #7A7A72; }

/* ── Fields ── */
.login-field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 20px; }
.login-field label {
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: #7A7A72;
}
.login-field-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.login-forgot {
  font-size: 11px;
  font-weight: 700;
  color: #E8180C;
  text-decoration: none;
  transition: color .2s;
}
.login-forgot:hover { color: #0A0A0A; }

.login-input-wrap { position: relative; }
.login-input-icon {
  position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
  width: 15px; height: 15px; color: #D4D4D0; pointer-events: none;
}
.login-input {
  width: 100%;
  padding: 11px 40px 11px 36px;
  border: 1.5px solid #D4D4D0;
  font-family: 'DM Sans', sans-serif;
  font-size: 14px; color: #0A0A0A;
  background: #F2F2F0;
  outline: none;
  transition: border-color .2s, background .2s;
}
.login-input::placeholder { color: #D4D4D0; }
.login-input:focus { border-color: #0A0A0A; background: #fff; }
.login-field.has-error .login-input { border-color: #E8180C; }
.login-error { font-size: 11px; color: #E8180C; font-weight: 600; }

.login-eye {
  position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer;
  color: #D4D4D0; display: flex; transition: color .2s;
}
.login-eye:hover { color: #0A0A0A; }
.login-eye svg { width: 15px; height: 15px; }

/* ── Remember ── */
.login-remember {
  display: flex; align-items: center; gap: 9px;
  cursor: pointer; margin-bottom: 24px;
  font-size: 13px; color: #7A7A72;
}
.login-remember-check { display: none; }
.login-remember-box {
  width: 16px; height: 16px;
  border: 1.5px solid #D4D4D0;
  background: #fff; flex-shrink: 0; position: relative;
  transition: all .15s;
}
.login-remember-check:checked + .login-remember-box { background: #0A0A0A; border-color: #0A0A0A; }
.login-remember-check:checked + .login-remember-box::after {
  content: ''; position: absolute;
  left: 4px; top: 1px; width: 5px; height: 9px;
  border: 2px solid #fff; border-top: none; border-left: none;
  transform: rotate(45deg);
}

/* ── Button ── */
.login-btn {
  width: 100%;
  display: flex; align-items: center; justify-content: center; gap: 10px;
  padding: 14px 24px;
  background: #E8180C; color: #fff;
  border: 2px solid #E8180C;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 18px; letter-spacing: 2px;
  cursor: pointer;
  box-shadow: 4px 4px 0 #0A0A0A;
  transition: all .2s;
}
.login-btn:hover:not(:disabled) {
  background: #0A0A0A; border-color: #0A0A0A;
  transform: translate(-2px,-2px);
  box-shadow: 6px 6px 0 #E8180C;
}
.login-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.login-btn svg { width: 16px; height: 16px; }

/* Loading dots */
.login-dots { display: inline-flex; gap: 4px; }
.login-dots span { width: 5px; height: 5px; background: #fff; border-radius: 50%; animation: ldots 1s ease-in-out infinite; }
.login-dots span:nth-child(2) { animation-delay: .15s; }
.login-dots span:nth-child(3) { animation-delay: .3s; }
@keyframes ldots { 0%,80%,100%{transform:scale(.7);opacity:.5} 40%{transform:scale(1.1);opacity:1} }

/* ── Alert ── */
.login-alert {
  display: flex; align-items: center; gap: 9px;
  margin-top: 16px; padding: 12px 14px;
  border: 1.5px solid #E8180C;
  background: #fff5f5;
  font-size: 13px; color: #E8180C; font-weight: 600;
}
.login-alert svg { width: 15px; height: 15px; flex-shrink: 0; }

/* ── Bottom ── */
.login-form-bottom {
  text-align: center;
  margin-top: 28px;
  font-size: 13px; color: #7A7A72;
  padding-top: 20px;
  border-top: 1px solid #F2F2F0;
}
.login-register-link {
  display: block;
  margin-top: 8px;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 15px; letter-spacing: 1.5px;
  color: #0A0A0A; text-decoration: none;
  transition: color .2s;
}
.login-register-link:hover { color: #E8180C; }

/* ── Responsive ── */
@media (max-width: 860px) {
  .login-layout { grid-template-columns: 1fr; }
  .login-brand { display: none; }
  .login-form-panel { border-left: none; padding: 40px 24px; }
}
</style>
