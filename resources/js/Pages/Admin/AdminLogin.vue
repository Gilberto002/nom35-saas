<template>
  <div class="login-shell">
    <div class="login-bg">
      <div class="bg-orb orb-1"></div>
      <div class="bg-orb orb-2"></div>
      <div class="bg-grid"></div>
    </div>

    <div class="login-card">
      <div class="login-logo">
        <svg width="36" height="36" viewBox="0 0 28 28" fill="none">
          <rect width="28" height="28" rx="8" fill="#6366F1"/>
          <path d="M7 14L13 20L21 8" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <div>
          <span class="login-brand">NOM-035</span>
          <span class="login-brand-sub">Super Admin</span>
        </div>
      </div>

      <h1 class="login-title">Acceso restringido</h1>
      <p class="login-sub">Solo personal autorizado</p>

      <div v-if="errorMsg" class="login-error">
        <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"/><path d="M8 5v3.5M8 11h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        {{ errorMsg }}
      </div>

      <div class="login-form">
        <div class="lf-field">
          <label>Correo electrónico</label>
          <input v-model="form.email" type="email" placeholder="admin@nom035.mx"
            class="lf-input" :class="{ error: errorMsg }" @keyup.enter="handleLogin"/>
        </div>

        <div class="lf-field">
          <label>Contraseña</label>
          <div class="lf-input-wrap">
            <input v-model="form.password" :type="showPass ? 'text' : 'password'"
              placeholder="••••••••" class="lf-input" :class="{ error: errorMsg }"
              @keyup.enter="handleLogin"/>
            <button class="lf-toggle-pass" @click="showPass = !showPass" type="button">
              <svg v-if="!showPass" viewBox="0 0 16 16" fill="none">
                <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="currentColor" stroke-width="1.2"/>
                <circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.2"/>
              </svg>
              <svg v-else viewBox="0 0 16 16" fill="none">
                <path d="M2 2l12 12M6.5 6.7A2 2 0 0 0 9.3 9.5M4.2 4.4C2.8 5.4 2 7 2 7s2 4 6 4c.9 0 1.8-.2 2.5-.5M7 3.1C7.3 3 7.7 3 8 3c4 0 6 4 6 4s-.5 1-1.5 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
              </svg>
            </button>
          </div>
        </div>

        <button class="btn-login" @click="handleLogin" :disabled="isLoading">
          <span v-if="!isLoading">Iniciar sesión</span>
          <span v-else class="dots"><span></span><span></span><span></span></span>
        </button>
      </div>

      <p class="login-footer-note">
        <svg viewBox="0 0 14 14" fill="none"><rect x="2" y="6" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M4.5 6V4.5a2.5 2.5 0 0 1 5 0V6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
        Sesión cifrada · Accesos monitoreados
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

const form      = reactive({ email: '', password: '' })
const errorMsg  = ref('')
const isLoading = ref(false)
const showPass  = ref(false)

async function handleLogin() {
  if (!form.email || !form.password) {
    errorMsg.value = 'Completa todos los campos'
    return
  }
  isLoading.value = true
  errorMsg.value  = ''
  try {
    const res  = await fetch('/api/admin/login', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body:    JSON.stringify({ email: form.email, password: form.password }),
    })
    const data = await res.json()
    if (!res.ok) {
      errorMsg.value = data.message || 'Credenciales incorrectas'
      return
    }
    localStorage.setItem('admin_token', data.token)
    localStorage.setItem('admin_user',  JSON.stringify(data.admin))
    window.location.href = '/admin/dashboard'
  } catch {
    errorMsg.value = 'Error de conexión, intenta de nuevo'
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700;800&display=swap');
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.login-shell {
  min-height: 100vh; background: #0F0F1A;
  display: flex; align-items: center; justify-content: center;
  font-family: 'DM Sans', sans-serif; position: relative; overflow: hidden;
}
.login-bg { position: absolute; inset: 0; pointer-events: none; }
.bg-orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.25; }
.orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, #6366F1, transparent); top: -150px; left: -150px; }
.orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, #8B5CF6, transparent); bottom: -100px; right: -100px; }
.bg-grid {
  position: absolute; inset: 0;
  background-image: linear-gradient(rgba(99,102,241,0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.04) 1px, transparent 1px);
  background-size: 40px 40px;
}
.login-card {
  position: relative; z-index: 1; width: 100%; max-width: 400px;
  background: #13131F; border: 1px solid rgba(255,255,255,0.08);
  border-radius: 20px; padding: 36px 32px; box-shadow: 0 32px 80px rgba(0,0,0,0.5);
}
.login-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 28px; }
.login-brand { display: block; font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 800; color: white; line-height: 1; }
.login-brand-sub { display: block; font-size: 9px; color: #6366F1; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; margin-top: 2px; }
.login-title { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; color: white; margin-bottom: 4px; }
.login-sub { font-size: 13px; color: #475569; margin-bottom: 24px; }
.login-error {
  display: flex; align-items: center; gap: 8px;
  background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25);
  color: #FCA5A5; font-size: 12px; padding: 10px 14px; border-radius: 9px; margin-bottom: 16px;
}
.login-error svg { width: 14px; height: 14px; flex-shrink: 0; }
.login-form { display: flex; flex-direction: column; gap: 14px; }
.lf-field { display: flex; flex-direction: column; gap: 6px; }
.lf-field label { font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
.lf-input-wrap { position: relative; }
.lf-input {
  width: 100%; padding: 10px 14px; background: #0F0F1A;
  border: 1px solid rgba(255,255,255,0.1); border-radius: 9px; color: #E2E8F0;
  font-family: 'DM Sans', sans-serif; font-size: 14px; outline: none; transition: border-color 0.2s;
}
.lf-input-wrap .lf-input { padding-right: 42px; }
.lf-input:focus { border-color: #6366F1; }
.lf-input.error { border-color: rgba(239,68,68,0.5); }
.lf-input::placeholder { color: #334155; }
.lf-toggle-pass {
  position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer; color: #475569; display: flex; padding: 2px; transition: color 0.2s;
}
.lf-toggle-pass:hover { color: #94A3B8; }
.lf-toggle-pass svg { width: 15px; height: 15px; }
.btn-login {
  margin-top: 6px; width: 100%; padding: 12px; background: #6366F1; color: white;
  border: none; border-radius: 10px; font-family: 'DM Sans', sans-serif;
  font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;
  display: flex; align-items: center; justify-content: center; min-height: 44px;
}
.btn-login:hover:not(:disabled) { background: #4F46E5; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(99,102,241,0.35); }
.btn-login:disabled { opacity: 0.6; cursor: not-allowed; }
.dots { display: inline-flex; gap: 4px; }
.dots span { width: 5px; height: 5px; background: white; border-radius: 50%; animation: bounce 1s ease-in-out infinite; }
.dots span:nth-child(2) { animation-delay: 0.15s; }
.dots span:nth-child(3) { animation-delay: 0.3s; }
@keyframes bounce { 0%,80%,100%{transform:scale(0.8);opacity:.5} 40%{transform:scale(1.1);opacity:1} }
.login-footer-note { display: flex; align-items: center; gap: 6px; justify-content: center; margin-top: 20px; font-size: 11px; color: #334155; }
.login-footer-note svg { width: 12px; height: 12px; }
</style>