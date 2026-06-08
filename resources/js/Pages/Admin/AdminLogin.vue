<template>
  <div class="shell">

    <!-- ── PANEL IZQUIERDO ── -->
    <div class="left">
      <div class="left-geo">
        <div class="geo-circle" style="width:320px;height:320px;top:-110px;left:-110px;"></div>
        <div class="geo-circle" style="width:200px;height:200px;bottom:30px;right:-70px;"></div>
        <div class="geo-line" style="height:1px;width:100%;top:35%;"></div>
      </div>

      <div class="left-top">
        <div class="logo-wrap">
          <div class="logo-mark">
            <svg width="20" height="20" viewBox="0 0 16 16" fill="none">
              <path d="M2 8L6 4L10 8L14 4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M2 12L6 8L10 12L14 8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
            </svg>
          </div>
          <span class="brand">Gioncore</span>
        </div>
        <div class="sa-badge">
          <div class="sa-dot"></div>
          <span class="sa-text">Super Admin</span>
        </div>
      </div>

      <div class="left-mid">
        <span class="left-tag">NOM-035 · STPS · 2018</span>
        <h2 class="left-title">Panel de<br/>control<br/><span class="accent">restringido</span></h2>
        <p class="left-desc">Acceso exclusivo para administradores con privilegios máximos del sistema Gioncore.</p>
      </div>

      <div class="divider"></div>
      <div class="stats">
        <div class="stat">
          <span class="stat-val">AES-256</span>
          <span class="stat-lbl">Cifrado activo</span>
        </div>
        <div class="stat">
          <span class="stat-val">NOM-035</span>
          <span class="stat-lbl">Cumplimiento</span>
        </div>
        <div class="stat">
          <span class="stat-val">ISO 45001</span>
          <span class="stat-lbl">Alineado</span>
        </div>
      </div>
    </div>

    <!-- ── PANEL DERECHO ── -->
    <div class="right">
      <div class="right-deco"></div>
      <div class="right-deco2"></div>

      <div class="form-wrap">

        <div class="shield-icon">
          <svg width="16" height="16" viewBox="0 0 14 14" fill="none">
            <path d="M7 1L12 3V7C12 10 7 13 7 13C7 13 2 10 2 7V3L7 1Z" stroke="#0B1F4A" stroke-width="1.3" stroke-linejoin="round"/>
            <path d="M5 7L6.5 8.5L9 5.5" stroke="#C41E3A" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>

        <div class="form-eyebrow">
          <div class="eyebrow-line"></div>
          <span class="eyebrow-text">Acceso Super Admin</span>
          <div class="eyebrow-line"></div>
        </div>

        <h1 class="form-title">Bienvenido</h1>
        <p class="form-sub">Ingresa tus credenciales con privilegios máximos</p>

        <div v-if="errorMsg" class="error-box">
          <svg width="13" height="13" viewBox="0 0 14 14" fill="none">
            <circle cx="7" cy="7" r="6" stroke="currentColor" stroke-width="1.3"/>
            <path d="M7 4.5V7M7 9.2h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
          {{ errorMsg }}
        </div>

        <div class="fields">
          <div class="field">
            <label class="lbl">Correo electrónico</label>
            <div class="inp-wrap">
              <span class="inp-ico">
                <svg width="13" height="13" viewBox="0 0 14 14" fill="none">
                  <rect x="1" y="3" width="12" height="8" rx="1.5" stroke="currentColor" stroke-width="1.2"/>
                  <path d="M1 4.5L7 8.5L13 4.5" stroke="currentColor" stroke-width="1.2"/>
                </svg>
              </span>
              <input
                v-model="form.email"
                type="email"
                placeholder="superadmin@gioncore.com"
                class="inp"
                :class="{ err: errorMsg }"
                @keyup.enter="handleLogin"
              />
            </div>
          </div>

          <div class="field">
            <label class="lbl">Contraseña</label>
            <div class="inp-wrap">
              <span class="inp-ico">
                <svg width="13" height="13" viewBox="0 0 14 14" fill="none">
                  <rect x="2" y="6" width="10" height="7" rx="1.2" stroke="currentColor" stroke-width="1.2"/>
                  <path d="M4.5 6V4.5a2.5 2.5 0 0 1 5 0V6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
              </span>
              <input
                v-model="form.password"
                :type="showPass ? 'text' : 'password'"
                placeholder="••••••••"
                class="inp inp-pass"
                :class="{ err: errorMsg }"
                @keyup.enter="handleLogin"
              />
              <button class="eye-btn" @click="showPass = !showPass" type="button">
                <svg v-if="!showPass" width="15" height="15" viewBox="0 0 16 16" fill="none">
                  <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="currentColor" stroke-width="1.2"/>
                  <circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.2"/>
                </svg>
                <svg v-else width="15" height="15" viewBox="0 0 16 16" fill="none">
                  <path d="M2 2l12 12M6.5 6.7A2 2 0 0 0 9.3 9.5M4.2 4.4C2.8 5.4 2 7 2 7s2 4 6 4c.9 0 1.8-.2 2.5-.5M7 3.1C7.3 3 7.7 3 8 3c4 0 6 4 6 4s-.5 1-1.5 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <button class="btn" @click="handleLogin" :disabled="isLoading">
          <span v-if="!isLoading" class="btn-inner">
            Acceder al sistema
            <svg width="13" height="13" viewBox="0 0 14 14" fill="none">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
          <span v-else class="dots">
            <span></span><span></span><span></span>
          </span>
        </button>

        <div class="footer-note">
          <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
            <rect x="1.5" y="5.5" width="9" height="6" rx="1.2" stroke="currentColor" stroke-width="1.1"/>
            <path d="M4 5.5V4A2 2 0 0 1 8 4v1.5" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
          </svg>
          Sesión cifrada · Accesos monitoreados · gioncore.com
        </div>

      </div>
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
    errorMsg.value = 'Completa todos los campos para continuar'
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
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Cormorant+Garamond:wght@600;700&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.shell {
  min-height: 100vh;
  display: flex;
  font-family: 'Outfit', sans-serif;
  background: #fff;
}

/* ── LEFT ── */
.left {
  width: 420px;
  flex-shrink: 0;
  background: #0B1F4A;
  display: flex;
  flex-direction: column;
  padding: 44px 44px 40px;
  position: relative;
  overflow: hidden;
}
.left-geo { position: absolute; inset: 0; pointer-events: none; }
.geo-circle { position: absolute; border-radius: 50%; border: 1px solid rgba(255,255,255,0.05); }
.geo-line { position: absolute; background: rgba(255,255,255,0.04); left: 0; }

.left-top {
  position: relative; z-index: 1;
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 60px;
  animation: fadeUp .5s ease both;
}
.logo-wrap { display: flex; align-items: center; gap: 10px; }
.logo-mark {
  width: 36px; height: 36px; background: #C41E3A;
  border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.brand { font-size: 18px; font-weight: 700; color: #fff; letter-spacing: .3px; }

.sa-badge {
  display: flex; align-items: center; gap: 6px;
  background: rgba(196,30,58,0.15);
  border: 1px solid rgba(196,30,58,0.3);
  padding: 5px 12px; border-radius: 20px;
}
.sa-dot {
  width: 6px; height: 6px; background: #C41E3A; border-radius: 50%;
  animation: pulse 2s ease-in-out infinite;
}
.sa-text { font-size: 9px; font-weight: 700; color: #C41E3A; letter-spacing: 1.5px; text-transform: uppercase; }

.left-mid {
  position: relative; z-index: 1;
  flex: 1; display: flex; flex-direction: column; justify-content: center;
}
.left-tag {
  display: inline-block; font-size: 9px; font-weight: 700;
  letter-spacing: 2.5px; text-transform: uppercase;
  color: rgba(255,255,255,0.32); margin-bottom: 16px;
  animation: fadeUp .5s .1s ease both;
}
.left-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: 40px; font-weight: 700;
  color: #fff; line-height: 1.18; margin-bottom: 18px;
  animation: fadeUp .5s .15s ease both;
}
.left-title .accent { color: #C41E3A; }
.left-desc {
  font-size: 13px; font-weight: 300;
  color: rgba(255,255,255,.38); line-height: 1.75;
  animation: fadeUp .5s .2s ease both;
}

.divider {
  position: relative; z-index: 1;
  height: 1px; background: rgba(255,255,255,.07);
  margin: 36px 0;
  animation: fadeUp .5s .25s ease both;
}
.stats {
  position: relative; z-index: 1;
  display: flex; gap: 0;
  animation: fadeUp .5s .3s ease both;
}
.stat { flex: 1; display: flex; flex-direction: column; gap: 3px; padding-right: 20px; }
.stat + .stat { padding-left: 20px; padding-right: 0; border-left: 1px solid rgba(255,255,255,.07); }
.stat-val { font-size: 12px; font-weight: 700; color: #fff; letter-spacing: .5px; }
.stat-lbl { font-size: 10px; color: rgba(255,255,255,.28); }

/* ── RIGHT ── */
.right {
  flex: 1; display: flex; align-items: center; justify-content: center;
  padding: 40px 56px; background: #fff;
  position: relative; overflow: hidden;
}
.right-deco {
  position: absolute; top: 0; right: 0;
  width: 200px; height: 200px; border-radius: 50%;
  background: #FEF2F4; transform: translate(70px,-70px);
}
.right-deco2 {
  position: absolute; bottom: 0; left: 0;
  width: 140px; height: 140px; border-radius: 50%;
  background: #EEF2FA; transform: translate(-50px,50px);
}

.form-wrap {
  width: 100%; max-width: 360px;
  position: relative; z-index: 1;
  animation: slideIn .55s .1s ease both;
}

.shield-icon {
  display: flex; align-items: center; justify-content: center;
  width: 36px; height: 36px;
  background: #EEF2FA; border-radius: 8px; margin-bottom: 24px;
}

.form-eyebrow { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.eyebrow-line { flex: 1; height: 1px; background: #EEF2FA; }
.eyebrow-text {
  font-size: 9px; font-weight: 700; letter-spacing: 2px;
  text-transform: uppercase; color: #C41E3A; white-space: nowrap;
}

.form-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: 30px; font-weight: 700; color: #0B1F4A; margin-bottom: 4px;
}
.form-sub { font-size: 12px; color: #94A3B8; margin-bottom: 28px; }

.error-box {
  display: flex; align-items: center; gap: 8px;
  background: #FEF2F4; border-left: 3px solid #C41E3A;
  border-radius: 0 6px 6px 0; color: #9B1B2E;
  font-size: 12px; font-weight: 500;
  padding: 10px 14px; margin-bottom: 18px;
  animation: shake .35s ease;
}

.fields { display: flex; flex-direction: column; gap: 16px; margin-bottom: 22px; }
.field  { display: flex; flex-direction: column; gap: 6px; }
.lbl { font-size: 10px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 1.2px; }
.inp-wrap { position: relative; }
.inp-ico {
  position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
  color: #CBD5E1; display: flex; align-items: center;
}
.inp {
  width: 100%; padding: 12px 13px 12px 38px;
  background: #F8FAFD; border: 1.5px solid #E2E8F0; border-radius: 8px;
  color: #0B1F4A; font-family: 'Outfit', sans-serif; font-size: 13.5px; outline: none;
  transition: border-color .2s, box-shadow .2s, background .2s;
}
.inp:focus { border-color: #0B1F4A; background: #fff; box-shadow: 0 0 0 3px rgba(11,31,74,.06); }
.inp.err  { border-color: #C41E3A; background: #FEF2F4; }
.inp::placeholder { color: #CBD5E1; }
.inp-pass { padding-right: 44px; }
.eye-btn {
  position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer; color: #94A3B8;
  display: flex; align-items: center; padding: 2px; transition: color .2s;
}
.eye-btn:hover { color: #0B1F4A; }

.btn {
  width: 100%; padding: 14px; background: #0B1F4A; color: #fff;
  border: none; border-radius: 8px; font-family: 'Outfit', sans-serif;
  font-size: 13.5px; font-weight: 600; cursor: pointer; letter-spacing: .3px;
  display: flex; align-items: center; justify-content: center;
  min-height: 48px; margin-bottom: 20px;
  transition: background .2s, transform .15s, box-shadow .2s;
}
.btn:hover:not(:disabled) { background: #C41E3A; transform: translateY(-1px); box-shadow: 0 10px 28px rgba(196,30,58,.22); }
.btn:active:not(:disabled) { transform: translateY(0); box-shadow: none; }
.btn:disabled { opacity: .55; cursor: not-allowed; }
.btn-inner { display: flex; align-items: center; gap: 8px; }

.dots { display: inline-flex; gap: 5px; }
.dots span { width: 5px; height: 5px; background: #fff; border-radius: 50%; animation: bounce 1s ease-in-out infinite; }
.dots span:nth-child(2) { animation-delay: .15s; }
.dots span:nth-child(3) { animation-delay: .3s; }

.footer-note { display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 11px; color: #CBD5E1; }

/* ── KEYFRAMES ── */
@keyframes fadeUp   { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
@keyframes slideIn  { from { opacity:0; transform:translateX(22px); } to { opacity:1; transform:translateX(0); } }
@keyframes pulse    { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:.5; transform:scale(.8); } }
@keyframes bounce   { 0%,80%,100% { transform:scale(.8); opacity:.4; } 40% { transform:scale(1.1); opacity:1; } }
@keyframes shake    { 0%,100% { transform:translateX(0); } 25% { transform:translateX(-6px); } 75% { transform:translateX(6px); } }

/* ── RESPONSIVE ── */
@media (max-width: 820px) {
  .shell       { flex-direction: column; }
  .left        { width: 100%; padding: 28px 28px 32px; }
  .left-title  { font-size: 30px; }
  .left-desc   { display: none; }
  .left-top    { margin-bottom: 36px; }
  .left-mid    { padding: 24px 0 20px; }
  .right       { padding: 36px 28px; }
}
</style>