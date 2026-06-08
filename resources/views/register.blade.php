<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NOM-035 — Onboarding</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --black: #0A0A0A;
  --white: #FFFFFF;
  --red:   #E8180C;
  --gray:  #F2F2F0;
  --gray2: #D4D4D0;
  --muted: #7A7A72;
}

html { scroll-behavior: smooth; }
body {
  font-family: 'DM Sans', sans-serif;
  background: var(--white);
  color: var(--black);
  overflow-x: hidden;
  min-height: 100vh;
}

/* ── TICKER ── */
.ticker {
  background: var(--red);
  color: var(--white);
  padding: 8px 0;
  overflow: hidden;
  position: relative;
  z-index: 200;
}
.ticker-track {
  display: flex;
  white-space: nowrap;
  animation: ticker 22s linear infinite;
}
.ticker-item {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 12px;
  letter-spacing: 2.5px;
  padding: 0 28px;
}
.ticker-item::after { content: ' ✦'; opacity: 0.6; }
@keyframes ticker {
  from { transform: translateX(0); }
  to   { transform: translateX(-50%); }
}

/* ── NAV ── */
nav {
  position: sticky; top: 0; z-index: 100;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 48px; height: 60px;
  background: rgba(255,255,255,0.96);
  backdrop-filter: blur(12px);
  border-bottom: 2px solid var(--black);
}
.nav-logo {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 20px; letter-spacing: 3px;
  color: var(--black); text-decoration: none;
  display: flex; align-items: center; gap: 8px;
}
.nav-logo-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: var(--red);
  animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.2} }
.nav-right { display: flex; align-items: center; gap: 14px; }
.nav-hint { font-size: 12px; color: var(--muted); }
.nav-login {
  font-size: 13px; font-weight: 700;
  color: var(--red); text-decoration: none; letter-spacing: 0.3px;
  transition: color .2s;
}
.nav-login:hover { color: var(--black); }

/* ── STEP BAR ── */
.stepbar {
  display: flex; align-items: center; justify-content: center;
  padding: 22px 48px;
  border-bottom: 2px solid var(--black);
  background: var(--gray);
}
.stepbar-item { display: flex; align-items: center; }
.step-num {
  width: 30px; height: 30px;
  border: 2px solid var(--black); background: var(--white);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Bebas Neue', sans-serif; font-size: 15px;
  color: var(--muted); transition: all 0.3s; flex-shrink: 0;
}
.stepbar-item.is-active .step-num  { background: var(--black); color: var(--white); border-color: var(--black); }
.stepbar-item.is-done   .step-num  { background: var(--red);   color: var(--white); border-color: var(--red); }
.step-label {
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 1.5px;
  color: var(--muted); margin-left: 8px; white-space: nowrap;
}
.stepbar-item.is-active .step-label { color: var(--black); }
.stepbar-item.is-done   .step-label { color: var(--red); }
.step-connector {
  width: 72px; height: 2px;
  background: var(--gray2); margin: 0 16px;
  transition: background 0.3s;
}
.step-connector.is-filled { background: var(--red); }

/* ── STAGE ── */
.stage {
  max-width: 1080px; margin: 0 auto;
  padding: 52px 48px 80px;
  display: none;
}
.stage.is-active { display: block; animation: fadeIn .3s ease; }
@keyframes fadeIn { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:none} }
.stage--narrow { max-width: 720px; }

.stage-head { margin-bottom: 40px; }
.eyebrow {
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 3px;
  color: var(--red); margin-bottom: 8px;
}
.ob-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(52px, 6vw, 76px);
  line-height: 0.9; letter-spacing: 2px;
  margin-bottom: 14px;
}
.ob-title .outline {
  -webkit-text-stroke: 2.5px var(--black);
  color: transparent;
}
.ob-subtitle { font-size: 15px; color: var(--muted); line-height: 1.6; }

/* ── BILLING TOGGLE ── */
.billing-toggle {
  display: flex; align-items: center; gap: 14px;
  margin-bottom: 32px;
}
.billing-label { font-size: 14px; color: var(--muted); transition: color .2s; cursor: pointer; }
.billing-label.is-active { color: var(--black); font-weight: 700; }
.toggle-btn {
  width: 44px; height: 22px;
  background: var(--gray2); border: 2px solid var(--black);
  cursor: pointer; position: relative; transition: background 0.2s;
}
.toggle-btn.is-yearly { background: var(--black); }
.toggle-thumb {
  position: absolute; top: 2px; left: 2px;
  width: 14px; height: 14px;
  background: var(--white);
  transition: transform 0.2s;
}
.toggle-btn.is-yearly .toggle-thumb { transform: translateX(22px); }
.save-badge {
  background: var(--black); color: var(--white);
  font-size: 9px; font-weight: 800;
  letter-spacing: 1px; text-transform: uppercase;
  padding: 2px 7px; margin-left: 6px;
  vertical-align: middle;
}

/* ── PLANS ── */
.plans-grid {
  display: grid; grid-template-columns: repeat(3,1fr);
  border: 2px solid var(--black); margin-bottom: 24px;
}
.plan-col {
  padding: 32px 28px;
  border-right: 2px solid var(--black);
  cursor: pointer; position: relative;
  transition: background 0.2s;
  background: var(--white);
}
.plan-col:last-child { border-right: none; }
.plan-col:hover { background: var(--gray); }
.plan-col.is-selected { background: var(--black); color: var(--white); }
.plan-badge {
  position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
  background: var(--red); color: var(--white);
  font-size: 9px; font-weight: 800;
  letter-spacing: 2px; text-transform: uppercase;
  padding: 3px 14px; white-space: nowrap;
}
.plan-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
.plan-name {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 26px; letter-spacing: 1.5px;
}
.plan-tagline { font-size: 11px; color: var(--muted); margin-top: 2px; }
.plan-col.is-selected .plan-tagline { color: rgba(255,255,255,0.45); }
.plan-radio {
  width: 22px; height: 22px; border: 2px solid var(--black);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; transition: all 0.2s; background: var(--white);
}
.plan-col.is-selected .plan-radio { background: var(--red); border-color: var(--red); }
.plan-radio-check { display: none; }
.plan-col.is-selected .plan-radio-check { display: block; color: white; font-size: 12px; font-weight: 700; line-height: 1; }
.plan-price-row { display: flex; align-items: baseline; gap: 4px; margin-bottom: 6px; }
.plan-price {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 46px; letter-spacing: 1px; line-height: 1;
}
.plan-period { font-size: 13px; color: var(--muted); }
.plan-col.is-selected .plan-period { color: rgba(255,255,255,0.5); }
.plan-saving {
  font-size: 11px; font-weight: 700; color: var(--red);
  background: #fff0ef; padding: 2px 8px;
  display: inline-block; margin-bottom: 4px;
}
.plan-col.is-selected .plan-saving { background: rgba(232,24,12,0.2); }
.plan-divider { height: 2px; background: var(--gray2); margin: 18px 0; }
.plan-col.is-selected .plan-divider { background: rgba(255,255,255,0.1); }
.plan-feats { list-style: none; display: flex; flex-direction: column; gap: 9px; }
.plan-feats li { display: flex; align-items: center; gap: 9px; font-size: 12.5px; }
.plan-feats li.off { color: var(--muted); }
.plan-col.is-selected .plan-feats li.off { color: rgba(255,255,255,0.3); }
.feat-icon { font-size: 13px; flex-shrink: 0; font-weight: 700; }
.plan-feats li:not(.off) .feat-icon { color: var(--red); }
.plan-feats li.off .feat-icon { color: var(--gray2); }
.plan-col.is-selected .plan-feats li:not(.off) .feat-icon { color: #ff6b63; }

/* Trial strip */
.trial-strip {
  display: flex; align-items: center; gap: 9px;
  font-size: 13px; color: var(--muted);
  border: 2px solid var(--black); padding: 13px 18px;
  margin-bottom: 32px; background: var(--gray);
}
.trial-strip svg { width: 15px; height: 15px; flex-shrink: 0; }

/* ── FORM CARD ── */
.form-card { border: 2px solid var(--black); margin-bottom: 28px; }
.form-section { padding: 28px 32px; display: flex; flex-direction: column; gap: 16px; }
.form-divider { height: 2px; background: var(--black); }
.section-label {
  display: flex; align-items: center; gap: 9px;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 16px; letter-spacing: 1.5px; color: var(--black);
}
.section-icon {
  width: 28px; height: 28px; background: var(--black);
  display: flex; align-items: center; justify-content: center;
}
.section-icon svg { width: 14px; height: 14px; color: var(--white); stroke: var(--white); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.field { display: flex; flex-direction: column; gap: 5px; }
.field label {
  font-size: 10px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted);
}
.req { color: var(--red); }
.input, .select {
  padding: 10px 13px;
  border: 1.5px solid var(--gray2);
  font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--black);
  background: var(--white); outline: none;
  transition: border-color 0.2s; width: 100%;
}
.input:focus, .select:focus { border-color: var(--black); }
.field.has-error .input, .field.has-error .select { border-color: var(--red); }
.select { appearance: none; cursor: pointer; }
.error-msg { font-size: 11px; color: var(--red); font-weight: 600; }

/* Password */
.pass-wrap { position: relative; }
.pass-eye {
  position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer; color: var(--muted);
  display: flex; transition: color .2s;
}
.pass-eye:hover { color: var(--black); }
.pass-eye svg { width: 15px; height: 15px; }
.strength-row { display: flex; align-items: center; gap: 8px; margin-top: 5px; }
.strength-bars { display: flex; gap: 3px; }
.sbar { width: 26px; height: 3px; background: var(--gray2); transition: background .2s; }
.sbar.lv1 { background: var(--red); }
.sbar.lv2 { background: #F59E0B; }
.sbar.lv3 { background: #22C55E; }
.sbar.lv4 { background: #059669; }
.strength-label { font-size: 11px; font-weight: 700; }
.strength-label.lv1 { color: var(--red); }
.strength-label.lv2 { color: #F59E0B; }
.strength-label.lv3 { color: #22C55E; }
.strength-label.lv4 { color: #059669; }

/* Plan reminder */
.plan-reminder {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 32px;
  border-top: 2px solid var(--black);
  background: var(--gray);
}
.pr-info { display: flex; align-items: center; gap: 12px; }
.pr-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--red); }
.pr-title { font-size: 13px; font-weight: 700; }
.pr-sub { font-size: 11px; color: var(--muted); margin-top: 2px; }
.pr-change { font-size: 12px; font-weight: 700; color: var(--red); background: none; border: none; cursor: pointer; text-decoration: underline; text-underline-offset: 2px; }

/* Terms */
.terms-label {
  display: flex; align-items: flex-start; gap: 10px;
  cursor: pointer; padding: 16px 32px 20px;
}
.terms-check { display: none; }
.terms-box {
  width: 16px; height: 16px; border: 1.5px solid var(--gray2);
  background: var(--white); flex-shrink: 0; position: relative; margin-top: 2px;
  transition: all .15s;
}
.terms-check:checked + .terms-box { background: var(--black); border-color: var(--black); }
.terms-check:checked + .terms-box::after {
  content: ''; position: absolute;
  left: 4px; top: 1px; width: 5px; height: 9px;
  border: 2px solid var(--white); border-top: none; border-left: none;
  transform: rotate(45deg);
}
.terms-text { font-size: 13px; color: var(--muted); line-height: 1.5; }
.terms-link { color: var(--black); font-weight: 700; }

/* ── FOOTER NAV ── */
.ob-foot { display: flex; align-items: center; justify-content: space-between; padding: 0 0 24px; }
.btn-primary {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 14px 32px;
  background: var(--red); color: var(--white);
  border: 2px solid var(--red);
  font-family: 'Bebas Neue', sans-serif; font-size: 17px; letter-spacing: 1.5px;
  cursor: pointer; box-shadow: 4px 4px 0 var(--black);
  transition: all 0.2s;
}
.btn-primary:hover:not(:disabled) {
  background: var(--black); border-color: var(--black);
  transform: translate(-2px,-2px); box-shadow: 6px 6px 0 var(--red);
}
.btn-primary:disabled { opacity: 0.4; cursor: not-allowed; }
.btn-primary svg { width: 16px; height: 16px; }
.btn-back {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 20px;
  background: var(--white); color: var(--muted);
  border: 1.5px solid var(--gray2);
  font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600;
  cursor: pointer; transition: all .2s;
}
.btn-back:hover { border-color: var(--black); color: var(--black); }
.btn-back svg { width: 15px; height: 15px; }

/* Loading */
.dots { display: inline-flex; gap: 4px; }
.dots span { width: 5px; height: 5px; background: white; border-radius: 50%; animation: bounce 1s ease-in-out infinite; }
.dots span:nth-child(2) { animation-delay: .15s; }
.dots span:nth-child(3) { animation-delay: .3s; }
@keyframes bounce { 0%,80%,100%{transform:scale(.7);opacity:.5} 40%{transform:scale(1.1);opacity:1} }

/* ── SUCCESS ── */
.success-wrap {
  border: 2px solid var(--black);
  padding: 56px 48px; background: var(--white); text-align: center;
}
.success-icon { width: 72px; margin: 0 auto 28px; }
.success-icon svg { width: 72px; height: 72px; }
.check-draw {
  stroke-dasharray: 65; stroke-dashoffset: 65;
  animation: drawCheck .6s ease .3s forwards;
}
@keyframes drawCheck { to { stroke-dashoffset: 0; } }
.success-sub { font-size: 15px; color: var(--muted); line-height: 1.7; margin: 16px 0 32px; }
.summary-grid {
  display: grid; grid-template-columns: 1fr 1fr;
  border: 2px solid var(--black); text-align: left; margin-bottom: 32px;
}
.summary-item {
  padding: 16px 20px;
  border-right: 2px solid var(--black);
  border-bottom: 2px solid var(--black);
}
.summary-item:nth-child(even) { border-right: none; }
.summary-item:nth-child(3), .summary-item:nth-child(4) { border-bottom: none; }
.summary-label {
  font-size: 9px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 1.5px;
  color: var(--muted); display: block; margin-bottom: 4px;
}
.summary-value { font-size: 14px; font-weight: 600; }
.next-section { text-align: left; margin-bottom: 32px; }
.next-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 16px; letter-spacing: 1.5px; margin-bottom: 14px;
}
.next-list { border: 2px solid var(--black); }
.next-item {
  display: flex; align-items: flex-start; gap: 16px;
  padding: 14px 18px; border-bottom: 1px solid var(--gray2);
}
.next-item:last-child { border-bottom: none; }
.next-num {
  width: 24px; height: 24px; background: var(--black);
  color: var(--white); font-family: 'Bebas Neue', sans-serif;
  font-size: 14px; display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.next-label { font-size: 13px; font-weight: 700; }
.next-sub { font-size: 11px; color: var(--muted); margin-top: 2px; }
.btn-enter {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 15px 40px;
  background: var(--black); color: var(--white);
  border: 2px solid var(--black);
  font-family: 'Bebas Neue', sans-serif; font-size: 18px; letter-spacing: 2px;
  cursor: pointer; box-shadow: 4px 4px 0 var(--red);
  transition: all .2s; margin-bottom: 20px;
}
.btn-enter:hover {
  background: var(--red); border-color: var(--red);
  transform: translate(-2px,-2px); box-shadow: 6px 6px 0 var(--black);
}
.btn-enter svg { width: 16px; height: 16px; }
.success-note { font-size: 12px; color: var(--muted); }

/* responsive */
@media(max-width:900px){
  .plans-grid{ grid-template-columns:1fr; border:none; gap:2px; }
  .plan-col{ border-right:none; border:2px solid var(--black); }
  .stage{ padding:36px 24px 60px; }
}
@media(max-width:640px){
  nav{ padding:0 18px; }
  .stepbar{ padding:18px 16px; }
  .step-connector{ width:32px; margin:0 8px; }
  .step-label{ display:none; }
  .form-row{ grid-template-columns:1fr; }
  .summary-grid{ grid-template-columns:1fr; }
  .summary-item{ border-right:none; }
  .form-section{ padding:20px; }
  .success-wrap{ padding:36px 20px; }
  .plan-reminder{ flex-direction:column; align-items:flex-start; gap:10px; padding:16px 20px; }
  .terms-label{ padding:14px 20px 20px; }
}
</style>
</head>
<body>

<!-- TICKER -->
<div class="ticker">
  <div class="ticker-track">
    <span class="ticker-item">NOM-035-STPS-2018</span>
    <span class="ticker-item">3 días gratis</span>
    <span class="ticker-item">Sin tarjeta de crédito</span>
    <span class="ticker-item">Cumplimiento normativo</span>
    <span class="ticker-item">Plataforma certificada</span>
    <span class="ticker-item">+500 empresas activas</span>
    <span class="ticker-item">NOM-035-STPS-2018</span>
    <span class="ticker-item">3 días gratis</span>
    <span class="ticker-item">Sin tarjeta de crédito</span>
    <span class="ticker-item">Cumplimiento normativo</span>
    <span class="ticker-item">Plataforma certificada</span>
    <span class="ticker-item">+500 empresas activas</span>
  </div>
</div>

<!-- NAV -->
<nav>
  <a href="/" class="nav-logo">
    <span class="nav-logo-dot"></span>
    NOM-035 SaaS
  </a>
  <div class="nav-right">
    <span class="nav-hint">¿Ya tienes cuenta?</span>
    <a href="/login" class="nav-login">Iniciar sesión →</a>
  </div>
</nav>

<!-- STEP BAR -->
<div class="stepbar">
  <div class="stepbar-item" id="sb0">
    <div class="step-num">1</div>
    <span class="step-label">Elige tu plan</span>
    <div class="step-connector" id="sc0"></div>
  </div>
  <div class="stepbar-item" id="sb1">
    <div class="step-num">2</div>
    <span class="step-label">Crea tu cuenta</span>
    <div class="step-connector" id="sc1"></div>
  </div>
  <div class="stepbar-item" id="sb2">
    <div class="step-num">3</div>
    <span class="step-label">¡Listo!</span>
  </div>
</div>

<!-- ══ STEP 0: PLAN ══ -->
<div class="stage is-active" id="step0">
  <div class="stage-head">
    <p class="eyebrow">Paso 1 de 3</p>
    <h1 class="ob-title">ELIGE TU<br><span class="outline">PLAN</span></h1>
    <p class="ob-subtitle">3 días gratis en cualquier plan. Sin tarjeta de crédito.</p>
  </div>

  <div class="billing-toggle">
    <span class="billing-label is-active" id="bl-monthly">Mensual</span>
    <button class="toggle-btn" id="toggle-btn" onclick="toggleBilling()">
      <span class="toggle-thumb"></span>
    </button>
    <span class="billing-label" id="bl-yearly">
      Anual
      <span class="save-badge">−20%</span>
    </span>
  </div>

  <div class="plans-grid" id="plans-grid">
    <!-- injected by JS -->
  </div>

  <div class="trial-strip">
    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor">
      <circle cx="8" cy="8" r="6.5" stroke-width="1.3"/>
      <path d="M8 5v4M8 11h.01" stroke-width="1.4" stroke-linecap="round"/>
    </svg>
    Todos los planes incluyen <strong style="margin-left:4px">3 días de prueba gratuita</strong>. Cancela cuando quieras.
  </div>

  <div class="ob-foot">
    <div></div>
    <button class="btn-primary" id="step0-next" onclick="goToStep(1)" disabled>
      Continuar
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 8h10M8 3l5 5-5 5"/>
      </svg>
    </button>
  </div>
</div>

<!-- ══ STEP 1: CUENTA ══ -->
<div class="stage stage--narrow" id="step1">
  <div class="stage-head">
    <p class="eyebrow">Paso 2 de 3</p>
    <h1 class="ob-title">CREA TU<br><span class="outline">CUENTA</span></h1>
    <p class="ob-subtitle">Configura los datos de tu empresa y el acceso de administrador.</p>
  </div>

  <div class="form-card">
    <!-- Empresa -->
    <div class="form-section">
      <div class="section-label">
        <div class="section-icon">
          <svg viewBox="0 0 16 16" fill="none" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 14V7l7-5 7 5v7H1z"/><path d="M6 14V9h4v5"/>
          </svg>
        </div>
        Datos de la empresa
      </div>
      <div class="form-row">
        <div class="field" id="f-company_name">
          <label>Nombre de la empresa <span class="req">*</span></label>
          <input type="text" placeholder="Empresa SA de CV" class="input" id="company_name"/>
        </div>
        <div class="field">
          <label>RFC</label>
          <input type="text" placeholder="EMP900101ABC" class="input" maxlength="13"/>
        </div>
      </div>
      <div class="form-row">
        <div class="field" id="f-company_email">
          <label>Correo de la empresa <span class="req">*</span></label>
          <input type="email" placeholder="contacto@empresa.com" class="input" id="company_email"/>
        </div>
        <div class="field">
          <label>Teléfono</label>
          <input type="text" placeholder="55 1234 5678" class="input"/>
        </div>
      </div>
      <div class="field">
        <label>Industria</label>
        <select class="input select">
          <option value="">Selecciona la industria</option>
          <option>Manufactura</option><option>Servicios</option><option>Salud</option>
          <option>Educación</option><option>Construcción</option>
          <option>Logística y transporte</option><option>Retail / Comercio</option>
          <option>Tecnología</option><option>Finanzas</option>
          <option>Alimentos y bebidas</option><option>Gobierno</option><option>Otro</option>
        </select>
      </div>
    </div>

    <div class="form-divider"></div>

    <!-- Admin -->
    <div class="form-section">
      <div class="section-label">
        <div class="section-icon">
          <svg viewBox="0 0 16 16" fill="none" stroke-width="1.4" stroke-linecap="round">
            <circle cx="8" cy="5.5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/>
          </svg>
        </div>
        Cuenta del administrador
      </div>
      <div class="field" id="f-admin_name">
        <label>Nombre completo <span class="req">*</span></label>
        <input type="text" placeholder="María González" class="input" id="admin_name"/>
      </div>
      <div class="field" id="f-admin_email">
        <label>Correo del administrador <span class="req">*</span></label>
        <input type="email" placeholder="admin@empresa.com" class="input" id="admin_email"/>
      </div>
      <div class="form-row">
        <div class="field" id="f-password">
          <label>Contraseña <span class="req">*</span></label>
          <div class="pass-wrap">
            <input type="password" placeholder="Mínimo 8 caracteres" class="input" id="password" oninput="updateStrength(this.value)"/>
            <button type="button" class="pass-eye" onclick="togglePass()">
              <svg id="eye-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.3">
                <path d="M2 8s2-4 6-4 6 4 6 4-2 4-6 4-6-4-6-4z"/><circle cx="8" cy="8" r="2"/>
              </svg>
            </button>
          </div>
          <div class="strength-row" id="strength-row" style="display:none">
            <div class="strength-bars">
              <div class="sbar" id="sb-1"></div><div class="sbar" id="sb-2"></div>
              <div class="sbar" id="sb-3"></div><div class="sbar" id="sb-4"></div>
            </div>
            <span class="strength-label" id="strength-label"></span>
          </div>
        </div>
        <div class="field" id="f-confirm">
          <label>Confirmar contraseña <span class="req">*</span></label>
          <input type="password" placeholder="Repite tu contraseña" class="input" id="confirm"/>
        </div>
      </div>
    </div>

    <!-- Plan reminder -->
    <div class="plan-reminder" id="plan-reminder">
      <div class="pr-info">
        <div class="pr-dot"></div>
        <div>
          <p class="pr-title" id="pr-title">Plan Profesional — 3 días gratis</p>
          <p class="pr-sub" id="pr-sub">Luego $1,299 MXN/mes · Cancela cuando quieras</p>
        </div>
      </div>
      <button class="pr-change" onclick="goToStep(0)">Cambiar plan</button>
    </div>

    <!-- Terms -->
    <label class="terms-label" id="terms-label">
      <input type="checkbox" class="terms-check" id="terms-check"/>
      <span class="terms-box"></span>
      <span class="terms-text">
        Acepto los <a href="#" class="terms-link">Términos de Servicio</a> y el
        <a href="#" class="terms-link">Aviso de Privacidad</a>
      </span>
    </label>
  </div>

  <div class="ob-foot">
    <button class="btn-back" onclick="goToStep(0)">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M13 8H3M8 3l-5 5 5 5"/>
      </svg>
      Atrás
    </button>
    <button class="btn-primary" id="step1-next" onclick="submitForm()">
      Crear cuenta gratis
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 8h10M8 3l5 5-5 5"/>
      </svg>
    </button>
  </div>
</div>

<!-- ══ STEP 2: SUCCESS ══ -->
<div class="stage stage--narrow" id="step2">
  <div class="success-wrap">
    <div class="success-icon">
      <svg viewBox="0 0 72 72" fill="none">
        <circle cx="36" cy="36" r="34" fill="#0A0A0A"/>
        <path d="M20 36l12 12 20-22" stroke="#E8180C" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" class="check-draw"/>
      </svg>
    </div>
    <p class="eyebrow" style="text-align:center">¡Todo listo!</p>
    <h1 class="ob-title" style="text-align:center">CUENTA<br><span class="outline">CREADA</span></h1>
    <p class="success-sub" id="success-sub">
      Bienvenido a NOM-035 SaaS. Tu período de prueba de 3 días ha comenzado.
    </p>
    <div class="summary-grid">
      <div class="summary-item">
        <span class="summary-label">Empresa</span>
        <span class="summary-value" id="s-company">—</span>
      </div>
      <div class="summary-item">
        <span class="summary-label">Plan activo</span>
        <span class="summary-value" id="s-plan">—</span>
      </div>
      <div class="summary-item">
        <span class="summary-label">Prueba gratis hasta</span>
        <span class="summary-value" id="s-trial">—</span>
      </div>
      <div class="summary-item">
        <span class="summary-label">Correo de acceso</span>
        <span class="summary-value" id="s-email">—</span>
      </div>
    </div>
    <div class="next-section">
      <div class="next-title">Próximos pasos recomendados</div>
      <div class="next-list">
        <div class="next-item"><div class="next-num">1</div><div><p class="next-label">Crea tus áreas</p><p class="next-sub">Organiza tu empresa por departamentos</p></div></div>
        <div class="next-item"><div class="next-num">2</div><div><p class="next-label">Registra empleados</p><p class="next-sub">Agrega a los trabajadores de tu empresa</p></div></div>
        <div class="next-item"><div class="next-num">3</div><div><p class="next-label">Aplica evaluaciones</p><p class="next-sub">Comienza con la Guía II para todos</p></div></div>
        <div class="next-item"><div class="next-num">4</div><div><p class="next-label">Genera tu reporte</p><p class="next-sub">Descarga el PDF para auditoría STPS</p></div></div>
      </div>
    </div>
    <button class="btn-enter" onclick="alert('Redirigiendo al dashboard...')">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 8h10M8 3l5 5-5 5"/>
      </svg>
      Entrar al panel
    </button>
    <p class="success-note" id="s-note">Te enviamos un correo de confirmación.</p>
  </div>
</div>

<script>
const plans = [
  {
    id: 1, name: 'Básico', tagline: 'Para empresas pequeñas',
    price: 499, priceYearly: 399, featured: false,
    features: [
      { text: 'Hasta 25 empleados', ok: true },
      { text: 'Guías I, II y III', ok: true },
      { text: 'Evaluaciones ilimitadas', ok: true },
      { text: '3 reportes PDF por mes', ok: true },
      { text: 'Dashboard básico', ok: true },
      { text: 'Reportes ilimitados', ok: false },
      { text: 'Acceso API', ok: false },
    ]
  },
  {
    id: 2, name: 'Profesional', tagline: 'Para empresas medianas',
    price: 1299, priceYearly: 1039, featured: true,
    features: [
      { text: 'Hasta 100 empleados', ok: true },
      { text: 'Guías I, II y III', ok: true },
      { text: 'Evaluaciones ilimitadas', ok: true },
      { text: 'Reportes PDF ilimitados', ok: true },
      { text: 'Dashboard avanzado', ok: true },
      { text: 'Resultados por área', ok: true },
      { text: 'Acceso API', ok: false },
    ]
  },
  {
    id: 3, name: 'Empresarial', tagline: 'Para grandes empresas',
    price: 2999, priceYearly: 2399, featured: false,
    features: [
      { text: 'Hasta 500 empleados', ok: true },
      { text: 'Guías I, II y III', ok: true },
      { text: 'Evaluaciones ilimitadas', ok: true },
      { text: 'Reportes PDF ilimitados', ok: true },
      { text: 'Dashboard ejecutivo', ok: true },
      { text: 'Acceso API REST', ok: true },
      { text: 'Gestor de cuenta dedicado', ok: true },
    ]
  }
];

let currentStep = 0;
let selectedPlan = null;
let billing = 'monthly';
let showPass = false;

function renderPlans() {
  const grid = document.getElementById('plans-grid');
  grid.innerHTML = plans.map(p => {
    const price = billing === 'yearly' ? p.priceYearly : p.price;
    const saving = billing === 'yearly' ? `<div class="plan-saving">Ahorras $${(p.price - p.priceYearly)*12}/año</div>` : '';
    const badge = p.featured ? `<div class="plan-badge">★ Más popular</div>` : '';
    const sel = selectedPlan === p.id;
    const feats = p.features.map(f =>
      `<li class="${f.ok ? '' : 'off'}">
        <span class="feat-icon">${f.ok ? '✓' : '✗'}</span>${f.text}
      </li>`
    ).join('');
    return `
      <div class="plan-col${sel ? ' is-selected' : ''}" onclick="selectPlan(${p.id})">
        ${badge}
        <div class="plan-top">
          <div>
            <div class="plan-name">${p.name}</div>
            <div class="plan-tagline">${p.tagline}</div>
          </div>
          <div class="plan-radio${sel ? ' is-on' : ''}">
            <span class="plan-radio-check">${sel ? '✓' : ''}</span>
          </div>
        </div>
        <div class="plan-price-row">
          <span class="plan-price">$${price}</span>
          <span class="plan-period">MXN/mes</span>
        </div>
        ${saving}
        <div class="plan-divider"></div>
        <ul class="plan-feats">${feats}</ul>
      </div>`;
  }).join('');
}

function selectPlan(id) {
  selectedPlan = id;
  renderPlans();
  document.getElementById('step0-next').disabled = false;
  // Update reminder
  const p = plans.find(x => x.id === id);
  document.getElementById('pr-title').textContent = `Plan ${p.name} — 3 días gratis`;
  document.getElementById('pr-sub').textContent = `Luego $${p.price} MXN/mes · Cancela cuando quieras`;
}

function toggleBilling() {
  billing = billing === 'monthly' ? 'yearly' : 'monthly';
  document.getElementById('toggle-btn').classList.toggle('is-yearly', billing === 'yearly');
  document.getElementById('bl-monthly').classList.toggle('is-active', billing === 'monthly');
  document.getElementById('bl-yearly').classList.toggle('is-active', billing === 'yearly');
  renderPlans();
}

function goToStep(n) {
  document.getElementById('step' + currentStep).classList.remove('is-active');
  currentStep = n;
  document.getElementById('step' + currentStep).classList.add('is-active');
  updateStepBar();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateStepBar() {
  [0,1,2].forEach(i => {
    const el = document.getElementById('sb' + i);
    const num = el.querySelector('.step-num');
    el.classList.remove('is-active','is-done');
    if (i < currentStep) {
      el.classList.add('is-done');
      num.innerHTML = '✓';
    } else if (i === currentStep) {
      el.classList.add('is-active');
      num.innerHTML = i + 1;
    } else {
      num.innerHTML = i + 1;
    }
    const conn = document.getElementById('sc' + i);
    if (conn) conn.classList.toggle('is-filled', currentStep > i);
  });
}

function updateStrength(val) {
  const row = document.getElementById('strength-row');
  row.style.display = val ? 'flex' : 'none';
  let s = 0;
  if (val.length >= 8) s++;
  if (/[A-Z]/.test(val)) s++;
  if (/[0-9]/.test(val)) s++;
  if (/[^A-Za-z0-9]/.test(val)) s++;
  const labels = ['','Débil','Regular','Buena','Muy fuerte'];
  const classes = ['','lv1','lv2','lv3','lv4'];
  [1,2,3,4].forEach(i => {
    const bar = document.getElementById('sb-' + i);
    bar.className = 'sbar';
    if (s >= i) bar.classList.add(classes[s]);
  });
  const lbl = document.getElementById('strength-label');
  lbl.textContent = labels[s];
  lbl.className = 'strength-label ' + classes[s];
}

function togglePass() {
  showPass = !showPass;
  const inputs = [document.getElementById('password'), document.getElementById('confirm')];
  inputs.forEach(i => { if(i) i.type = showPass ? 'text' : 'password'; });
}

function submitForm() {
  const btn = document.getElementById('step1-next');
  btn.innerHTML = '<span class="dots"><span></span><span></span><span></span></span>';
  btn.disabled = true;
  // Fill success screen
  const name = document.getElementById('admin_name').value || 'Usuario';
  const email = document.getElementById('admin_email').value || '—';
  const company = document.getElementById('company_name').value || '—';
  const p = plans.find(x => x.id === selectedPlan) || plans[1];
  const trial = new Date(); trial.setDate(trial.getDate()+3);
  const trialStr = trial.toLocaleDateString('es-MX',{day:'2-digit',month:'long',year:'numeric'});
  document.getElementById('success-sub').innerHTML =
    `Bienvenido a NOM-035 SaaS, <strong>${name}</strong>.<br>Tu período de prueba de 3 días ha comenzado.`;
  document.getElementById('s-company').textContent = company;
  document.getElementById('s-plan').textContent = p.name;
  document.getElementById('s-trial').textContent = trialStr;
  document.getElementById('s-email').textContent = email;
  document.getElementById('s-note').innerHTML =
    `Te enviamos un correo de confirmación a <strong>${email}</strong>`;
  setTimeout(() => goToStep(2), 1200);
}

// init
renderPlans();
updateStepBar();
</script>
</body>
</html>
