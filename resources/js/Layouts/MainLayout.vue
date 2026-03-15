<template>
  <div class="app-shell">
    <!-- Sidebar -->
    <aside class="sidebar" :class="{ collapsed: sidebarCollapsed }">
      <div class="sidebar-header">
        <div class="sidebar-logo">
          <svg width="30" height="30" viewBox="0 0 32 32" fill="none">
            <rect width="32" height="32" rx="6" fill="#E8180C"/>
            <path d="M8 16L14 22L24 10" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span v-show="!sidebarCollapsed" class="sidebar-brand">NOM-035</span>
        </div>
        <button class="collapse-btn" @click="sidebarCollapsed = !sidebarCollapsed">
          <svg viewBox="0 0 20 20" fill="none">
            <path d="M4 6h12M4 10h12M4 14h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      <nav class="sidebar-nav">
        <div class="nav-section-label" v-show="!sidebarCollapsed">General</div>
        <Link v-for="item in navItems" :key="item.route" :href="item.route"
          class="nav-item" :class="{ active: currentPage === item.route }">
          <span class="nav-icon" v-html="item.icon"></span>
          <span v-show="!sidebarCollapsed" class="nav-label">{{ item.label }}</span>
          <span v-show="!sidebarCollapsed && item.badge" class="nav-badge">{{ item.badge }}</span>
        </Link>

        <div class="nav-divider"></div>
        <div class="nav-section-label" v-show="!sidebarCollapsed">Reportes</div>
        <Link v-for="item in reportItems" :key="item.route" :href="item.route"
          class="nav-item" :class="{ active: currentPage === item.route }">
          <span class="nav-icon" v-html="item.icon"></span>
          <span v-show="!sidebarCollapsed" class="nav-label">{{ item.label }}</span>
        </Link>
      </nav>

      <div class="sidebar-footer">
        <div class="user-info" v-show="!sidebarCollapsed">
          <div class="user-avatar">{{ userInitials }}</div>
          <div class="user-details">
            <span class="user-name">{{ userName }}</span>
            <span class="user-role">Administrador</span>
          </div>
        </div>
        <div class="user-avatar-sm" v-show="sidebarCollapsed">{{ userInitials }}</div>
        <button class="logout-btn" @click="handleLogout" :title="sidebarCollapsed ? 'Cerrar sesión' : ''">
          <svg viewBox="0 0 20 20" fill="none">
            <path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3M13 14l4-4-4-4M17 10H7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span v-show="!sidebarCollapsed">Salir</span>
        </button>
      </div>
    </aside>

    <!-- Main area -->
    <div class="main-area">
      <!-- Topbar -->
      <header class="topbar">
        <div class="topbar-left">
          <h1 class="page-title">{{ pageTitle }}</h1>
          <div class="breadcrumb">
            <span>Panel</span>
            <svg viewBox="0 0 16 16" fill="none"><path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            <span class="breadcrumb-current">{{ pageTitle }}</span>
          </div>
        </div>
        <div class="topbar-right">
          <button class="topbar-btn">
            <svg viewBox="0 0 20 20" fill="none">
              <path d="M10 2a6 6 0 016 6c0 3.5-1.5 5-2 7H6c-.5-2-2-3.5-2-7a6 6 0 016-6zM8 17a2 2 0 004 0" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
            <span class="notif-dot"></span>
          </button>
          <div class="topbar-company">
            <span class="company-badge">{{ companyName }}</span>
          </div>
        </div>
      </header>

      <!-- Page content slot -->
      <main class="page-content">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'

const props = defineProps({
  currentPage: { type: String, default: '/dashboard' },
  pageTitle: { type: String, default: 'Dashboard' },
})

const page = usePage()
const sidebarCollapsed = ref(false)
const userName = computed(() => page.props.auth?.user?.name || 'Usuario')
const companyName = computed(() => page.props.auth?.company?.name || 'Mi Empresa')
const userInitials = computed(() => userName.value.split(' ').map(n => n[0]).join('').slice(0,2).toUpperCase())

const navItems = [
  {
    route: '/dashboard', label: 'Dashboard',
    icon: `<svg viewBox="0 0 20 20" fill="none"><rect x="2" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="11" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="2" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/><rect x="11" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.4"/></svg>`,
  },
  {
    route: '/employees', label: 'Empleados',
    icon: `<svg viewBox="0 0 20 20" fill="none"><circle cx="10" cy="6" r="3" stroke="currentColor" stroke-width="1.4"/><path d="M3 17c0-3.3 3.1-6 7-6s7 2.7 7 6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>`,
    badge: null,
  },
  {
    route: '/areas', label: 'Áreas',
    icon: `<svg viewBox="0 0 20 20" fill="none"><path d="M3 7l7-4 7 4v9H3V7z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M8 16v-5h4v5" stroke="currentColor" stroke-width="1.4"/></svg>`,
  },
  {
    route: '/evaluations', label: 'Evaluaciones',
    icon: `<svg viewBox="0 0 20 20" fill="none"><path d="M5 4h10a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4"/><path d="M7 8h6M7 11h6M7 14h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>`,
  },
]
const reportItems = [
  {
    route: '/results', label: 'Resultados',
    icon: `<svg viewBox="0 0 20 20" fill="none"><path d="M3 15l4-5 4 3 4-7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  },
  {
    route: '/reports', label: 'Reportes PDF',
    icon: `<svg viewBox="0 0 20 20" fill="none"><path d="M5 2h7l4 4v12a1 1 0 01-1 1H5a1 1 0 01-1-1V3a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4"/><path d="M12 2v4h4M7 10h6M7 13h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>`,
  },
]

function handleLogout() {
  router.post('/logout')
}
function getCookie(name) {
  return document.cookie.split('; ').find(r => r.startsWith(name + '='))?.split('=')[1] || ''
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600;700&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.app-shell {
  display: flex;
  min-height: 100vh;
  background: #F2F2F0;
  font-family: 'DM Sans', sans-serif;
}

/* ══ Sidebar ══ */
.sidebar {
  width: 228px;
  background: #0A0A0A;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  transition: width 0.25s cubic-bezier(0.22, 1, 0.36, 1);
  position: sticky;
  top: 0;
  height: 100vh;
  overflow: hidden;
  /* grid texture */
  background-image:
    linear-gradient(rgba(255,255,255,0.022) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.022) 1px, transparent 1px);
  background-size: 28px 28px;
}
.sidebar.collapsed { width: 62px; }

/* red accent top strip */
.sidebar::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: #E8180C;
  z-index: 1;
  pointer-events: none;
}
/* faint right glow */
.sidebar::after {
  content: '';
  position: absolute; right: 0; top: 0; bottom: 0; width: 1px;
  background: rgba(232,24,12,0.25);
  pointer-events: none;
}

/* ── Header ── */
.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 13px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
  flex-shrink: 0;
  position: relative; z-index: 2;
}
.sidebar-logo { display: flex; align-items: center; gap: 10px; overflow: hidden; }
.sidebar-brand {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 19px; letter-spacing: 3px;
  color: #ffffff;
  white-space: nowrap;
}

.collapse-btn {
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.08);
  cursor: pointer;
  color: rgba(255,255,255,0.28);
  width: 27px; height: 27px;
  border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  transition: all 0.2s;
}
.collapse-btn:hover {
  background: rgba(255,255,255,0.1);
  color: #ffffff;
  border-color: rgba(255,255,255,0.15);
}
.collapse-btn svg { width: 14px; height: 14px; }

/* ── Nav ── */
.sidebar-nav {
  flex: 1;
  padding: 14px 9px;
  overflow-y: auto;
  scrollbar-width: none;
}
.sidebar-nav::-webkit-scrollbar { display: none; }

.nav-section-label {
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  color: rgba(255,255,255,0.18);
  padding: 0 9px;
  margin-bottom: 5px;
  margin-top: 8px;
  white-space: nowrap;
}
.nav-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 10px 0; }

.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 10px;
  border-radius: 7px;
  text-decoration: none;
  color: rgba(255,255,255,0.32);
  transition: background 0.15s, color 0.15s;
  white-space: nowrap;
  margin-bottom: 2px;
  font-size: 13px;
  font-weight: 600;
  position: relative;
  letter-spacing: 0.2px;
}
.nav-item:hover {
  background: rgba(255,255,255,0.06);
  color: rgba(255,255,255,0.72);
}
.nav-item.active {
  background: rgba(232,24,12,0.13);
  color: #ffffff;
}
.nav-item.active::before {
  content: '';
  position: absolute;
  left: 0; top: 22%; height: 56%;
  width: 2.5px;
  background: #E8180C;
  border-radius: 0 2px 2px 0;
}

.nav-icon {
  width: 17px; height: 17px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.nav-icon :deep(svg) { width: 17px; height: 17px; }
.nav-label { flex: 1; }
.nav-badge {
  background: #E8180C;
  color: #fff;
  font-size: 9px; font-weight: 700;
  padding: 2px 7px; border-radius: 100px;
}

/* ── Footer ── */
.sidebar-footer {
  padding: 12px 9px;
  border-top: 1px solid rgba(255,255,255,0.06);
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}
.user-info { display: flex; align-items: center; gap: 9px; flex: 1; overflow: hidden; }

.user-avatar, .user-avatar-sm {
  width: 32px; height: 32px;
  background: linear-gradient(135deg, #1a1a1a, #E8180C);
  border: 1.5px solid rgba(232,24,12,0.35);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 700; color: #fff;
  flex-shrink: 0;
  letter-spacing: 0.5px;
}
.user-avatar-sm { margin: 0 auto; }

.user-details { overflow: hidden; }
.user-name {
  display: block; font-size: 12px; font-weight: 600;
  color: rgba(255,255,255,0.8);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.user-role {
  display: block; font-size: 9px; font-weight: 700;
  letter-spacing: 1.5px; text-transform: uppercase;
  color: rgba(255,255,255,0.2);
  margin-top: 1px;
}

.logout-btn {
  display: flex; align-items: center; gap: 5px;
  background: none; border: none; cursor: pointer;
  color: rgba(255,255,255,0.2);
  font-size: 12px; font-family: 'DM Sans', sans-serif; font-weight: 600;
  padding: 6px 7px; border-radius: 6px;
  transition: all 0.18s; white-space: nowrap; flex-shrink: 0;
}
.logout-btn:hover { color: #E8180C; background: rgba(232,24,12,0.1); }
.logout-btn svg { width: 14px; height: 14px; flex-shrink: 0; }

/* ══ Main area ══ */
.main-area {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
}

/* ══ Topbar ══ */
.topbar {
  background: #ffffff;
  border-bottom: 1.5px solid #E4E4E0;
  padding: 0 28px;
  height: 58px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-shrink: 0;
  position: sticky;
  top: 0;
  z-index: 10;
}

.page-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 22px; letter-spacing: 1.5px;
  color: #0A0A0A;
  line-height: 1;
}
.breadcrumb {
  display: flex; align-items: center; gap: 4px;
  font-size: 11px; color: #7A7A72;
  margin-top: 2px; font-weight: 500;
}
.breadcrumb svg { width: 10px; height: 10px; }
.breadcrumb-current { color: #0A0A0A; font-weight: 700; }

.topbar-right { display: flex; align-items: center; gap: 10px; }

.topbar-btn {
  width: 35px; height: 35px;
  background: #F2F2F0;
  border: 1.5px solid #E4E4E0;
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; color: #7A7A72;
  position: relative; transition: all 0.18s;
}
.topbar-btn:hover {
  background: #ffffff;
  color: #0A0A0A;
  border-color: #C4C4C0;
}
.topbar-btn svg { width: 15px; height: 15px; }

.notif-dot {
  position: absolute; top: 7px; right: 7px;
  width: 6px; height: 6px;
  background: #E8180C; border-radius: 50%;
  border: 1.5px solid #ffffff;
  animation: notif-pulse 2.5s ease-in-out infinite;
}
@keyframes notif-pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%       { opacity: 0.5; transform: scale(0.85); }
}

.company-badge {
  background: #F2F2F0;
  border: 1.5px solid #E4E4E0;
  color: #0A0A0A;
  font-size: 12px; font-weight: 700;
  letter-spacing: 0.3px;
  padding: 5px 14px; border-radius: 100px;
}

/* ══ Page content ══ */
.page-content {
  flex: 1;
  padding: 24px 28px;
  overflow-y: auto;
}

@media (max-width: 768px) {
  .sidebar { position: fixed; z-index: 100; height: 100%; }
  .page-content { padding: 20px 16px; }
  .topbar { padding: 0 16px; }
}
</style>