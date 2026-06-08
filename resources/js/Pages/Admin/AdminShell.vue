<template>
  <div class="shell">

    <!-- ══════ SIDEBAR ══════ -->
    <aside class="sidebar">
      <div class="sidebar-top">
        <div class="brand-wrap">
          <div class="brand-icon">
            <svg width="18" height="18" viewBox="0 0 28 28" fill="none">
              <path d="M8 14L13 19L20 9" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div>
            <div class="brand-name">GIONCOREY</div>
            <div class="brand-role">NOM-035 · Admin</div>
          </div>
        </div>

        <nav class="nav">
          <div class="nav-section-label">General</div>
          <button v-for="item in navItems" :key="item.id" class="nav-item"
            :class="{ active: view === item.id }" @click="view = item.id">
            <span class="nav-icon" v-html="item.icon"></span>
            <span class="nav-label">{{ item.label }}</span>
            <span v-if="item.badge" class="nav-badge">{{ item.badge }}</span>
          </button>
        </nav>
      </div>

      <div class="sidebar-bottom">
        <div class="admin-chip">
          <div class="admin-avatar">{{ adminName.charAt(0) }}</div>
          <div class="admin-info">
            <div class="admin-name">{{ adminName }}</div>
            <div class="admin-email">{{ adminEmail }}</div>
          </div>
          <button class="logout-icon" @click="logout" title="Cerrar sesión">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
          </button>
        </div>
      </div>
    </aside>

    <!-- ══════ MAIN ══════ -->
    <div class="main">
      <header class="topbar">
        <div class="topbar-left">
          <div class="breadcrumb">
            <span class="bc-root">Gioncorey</span>
            <span class="bc-sep">/</span>
            <span class="bc-cur">{{ currentItem?.label }}</span>
          </div>
          <h1 class="page-title">{{ currentItem?.label }}</h1>
        </div>
        <div class="topbar-right">
          <div v-if="networkError" class="error-chip">{{ networkError }}</div>
          <span class="env-pill">● Producción</span>
        </div>
      </header>

      <main class="content">

        <!-- ══════ DASHBOARD ══════ -->
        <div v-if="view === 'dashboard'" class="fade-in">
          <div v-if="isLoading" class="loading-state"><div class="spinner"></div> Cargando datos...</div>
          <template v-else>

            <!-- KPIs -->
            <div class="kpi-grid">
              <div class="kpi-card">
                <div class="kpi-hd"><span class="kpi-lbl">Empresas activas</span><div class="kpi-ico kpi-green"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div></div>
                <div class="kpi-trend up">▲ +2 este mes</div>
                <div class="kpi-val">{{ stats.active_companies ?? 0 }}</div>
                <div class="kpi-sub kpi-sub-green">en operación</div>
              </div>
              <div class="kpi-card">
                <div class="kpi-hd"><span class="kpi-lbl">En trial</span><div class="kpi-ico kpi-amber"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div></div>
                <div class="kpi-trend up">▲ +1</div>
                <div class="kpi-val">{{ stats.trial_companies ?? 0 }}</div>
                <div class="kpi-sub kpi-sub-amber">período de prueba</div>
              </div>
              <div class="kpi-card">
                <div class="kpi-hd"><span class="kpi-lbl">Suspendidas</span><div class="kpi-ico kpi-red"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg></div></div>
                <div class="kpi-trend dn">▼ 1 suspendida</div>
                <div class="kpi-val">{{ stats.suspended ?? 0 }}</div>
                <div class="kpi-sub kpi-sub-red">requieren atención</div>
              </div>
              <div class="kpi-card">
                <div class="kpi-hd"><span class="kpi-lbl">MRR</span><div class="kpi-ico kpi-indigo"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
                <div class="kpi-trend up">▲ +4.8% vs mes anterior</div>
                <div class="kpi-val">${{ (stats.mrr ?? 0).toLocaleString('es-MX') }}</div>
                <div class="kpi-sub kpi-sub-indigo">ingresos mensuales</div>
              </div>
              <div class="kpi-card">
                <div class="kpi-hd"><span class="kpi-lbl">Total empleados</span><div class="kpi-ico kpi-blue"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div></div>
                <div class="kpi-trend up">▲ +58 este mes</div>
                <div class="kpi-val">{{ (stats.total_employees ?? 0).toLocaleString('es-MX') }}</div>
                <div class="kpi-sub">registrados</div>
              </div>
              <div class="kpi-card">
                <div class="kpi-hd"><span class="kpi-lbl">ARR proyectado</span><div class="kpi-ico kpi-purple"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div></div>
                <div class="kpi-trend up">▲ en crecimiento</div>
                <div class="kpi-val">${{ ((stats.mrr ?? 0) * 12 / 1000000).toFixed(2) }}M</div>
                <div class="kpi-sub kpi-sub-indigo">anualizado</div>
              </div>
            </div>

            <!-- Charts Row -->
            <div class="charts-row">
              <div class="chart-card">
                <div class="card-hd">
                  <div>
                    <div class="card-title">Ingresos mensuales (MRR)</div>
                    <div class="card-sub">Últimos 12 meses · MXN</div>
                  </div>
                  <div class="tabs">
                    <button class="tab" :class="{ active: chartType === 'bar' }" @click="chartType = 'bar'">Barras</button>
                    <button class="tab" :class="{ active: chartType === 'line' }" @click="chartType = 'line'">Línea</button>
                  </div>
                </div>
                <div style="position:relative;height:200px">
                  <canvas ref="mrrChart" role="img" aria-label="Gráfica de MRR mensual"></canvas>
                </div>
              </div>
              <div class="chart-card">
                <div class="card-hd">
                  <div>
                    <div class="card-title">Distribución de planes</div>
                    <div class="card-sub">Por empresa activa</div>
                  </div>
                </div>
                <div style="position:relative;height:160px;margin-bottom:12px">
                  <canvas ref="donutChart" role="img" aria-label="Donut de planes"></canvas>
                </div>
                <div class="dist-list">
                  <div v-for="p in (stats.plans_distribution || [])" :key="p.name" class="dist-row">
                    <div class="dist-meta"><span class="dist-dot" :style="{ background: p.color }"></span><span class="dist-name">{{ p.name }}</span></div>
                    <div class="dist-track"><div class="dist-bar" :style="{ width: (p.pct||0)+'%', background: p.color }"></div></div>
                    <div class="dist-right"><span class="dist-pct">{{ p.pct ?? 0 }}%</span><span class="dist-cnt">{{ p.active }} emp.</span></div>
                  </div>
                  <div v-if="!stats.plans_distribution?.length" class="empty-mini">Sin datos</div>
                </div>
              </div>
            </div>

            <!-- Bottom Row -->
            <div class="bottom-row">
              <div class="chart-card">
                <div class="card-hd"><div class="card-title">Actividad reciente</div></div>
                <div class="activity-feed">
                  <div v-for="a in (stats.recent_activity || [])" :key="a.id" class="act-row">
                    <div class="act-bullet" :class="'b-'+a.type"></div>
                    <div class="act-content">
                      <div class="act-text">{{ a.text }}</div>
                      <div class="act-time">{{ a.time }}</div>
                    </div>
                  </div>
                  <div v-if="!stats.recent_activity?.length" class="empty-mini">Sin actividad reciente</div>
                </div>
              </div>
              <div class="chart-card">
                <div class="card-hd">
                  <div class="card-title">Top empresas</div>
                  <button class="link-btn" @click="view='companies'">Ver todas →</button>
                </div>
                <table class="mini-table">
                  <thead><tr><th>Empresa</th><th>Plan</th><th>Estado</th></tr></thead>
                  <tbody>
                    <tr v-for="c in companies.slice(0,5)" :key="c.id">
                      <td>
                        <div class="cell-av">
                          <div class="av" :style="{background:avatarBg(c.name)}">{{c.name?.charAt(0)}}</div>
                          <div><div class="cn">{{c.name}}</div><div class="cs">{{c.employee_count}} empleados</div></div>
                        </div>
                      </td>
                      <td><span class="tag">{{ planLabel(c.subscription?.plan?.slug) }}</span></td>
                      <td><span class="pill" :class="c.subscription?.status||'inactive'">{{ statusLabel(c.subscription?.status) }}</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          </template>
        </div>

        <!-- ══════ EMPRESAS ══════ -->
        <div v-if="view === 'companies'" class="fade-in">
          <div class="toolbar">
            <div class="sw">
              <svg class="si" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              <input v-model="search" class="sf" placeholder="Buscar nombre, RFC, email…"/>
            </div>
            <select v-model="filterStatus" class="sel">
              <option value="">Todos los estados</option>
              <option value="active">Activas</option>
              <option value="trial">En Trial</option>
              <option value="suspended">Suspendidas</option>
            </select>
            <button class="btn-dark" @click="openCompanyModal()">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Nueva empresa
            </button>
          </div>
          <div class="tw">
            <div v-if="isLoading" class="loading-state"><div class="spinner"></div> Cargando…</div>
            <table v-else class="dt">
              <thead>
                <tr><th>Empresa</th><th>Email</th><th>Empleados</th><th>Plan</th><th>Estado</th><th>Vence</th><th class="tr">Acciones</th></tr>
              </thead>
              <tbody>
                <tr v-for="c in filteredCompanies" :key="c.id" class="dr">
                  <td>
                    <div class="cell-av">
                      <div class="av" :style="{background:avatarBg(c.name)}">{{c.name?.charAt(0)}}</div>
                      <div><div class="cn">{{c.name}}</div><div class="cs">{{c.rfc||'Sin RFC'}}</div></div>
                    </div>
                  </td>
                  <td class="cm">{{ c.email||'—' }}</td>
                  <td class="num">{{ c.employee_count??0 }}</td>
                  <td><span class="tag">{{ planLabel(c.subscription?.plan?.slug) }}</span></td>
                  <td><span class="pill" :class="c.subscription?.status||'inactive'">{{ statusLabel(c.subscription?.status) }}</span></td>
                  <td class="cm">{{ formatDate(c.subscription?.current_period_end) }}</td>
                  <td>
                    <div class="icon-btns" style="justify-content:flex-end">
                      <button class="icon-btn" title="Editar" @click="openCompanyModal(c)">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      </button>
                      <button class="icon-btn" :class="c.subscription?.status==='active'?'danger':'success'"
                        :title="c.subscription?.status==='active'?'Suspender':'Activar'"
                        @click="toggleStatus(c)" :disabled="!c.subscription">
                        <svg v-if="c.subscription?.status==='active'" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                        <svg v-else width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                      </button>
                      <button class="icon-btn danger" title="Eliminar" @click="deleteCompany(c)">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="!filteredCompanies.length">
                  <td colspan="7" class="empty-row">No se encontraron empresas.</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="tf">{{ filteredCompanies.length }} de {{ companies.length }} empresas</div>
        </div>

        <!-- ══════ PLANES ══════ -->
        <div v-if="view === 'plans'" class="fade-in">
          <div class="toolbar">
            <div style="flex:1"></div>
            <button class="btn-dark" @click="openPlanModal()">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Nuevo plan
            </button>
          </div>
          <div v-if="isLoading" class="loading-state"><div class="spinner"></div> Cargando…</div>
          <div v-else class="plans-grid">
            <div v-for="(plan, i) in plans" :key="plan.id" class="plan-card" :class="{'plan-off':!plan.active}">
              <div class="plan-hero" :style="{ background: planHeroGradient(i) }">
                <div class="plan-badge" :style="{ background: planBadgeBg(i), color: planBadgeColor(i) }">
                  {{ plan.active ? 'Activo' : 'Inactivo' }}
                </div>
                <div class="plan-nm">{{ plan.name }}</div>
                <div class="plan-slug-txt">{{ plan.slug }}</div>
                <div class="plan-price-row">
                  <span class="plan-price-big" :style="{ color: getPlanColor(plan.slug) }">
                    ${{ plan.price.toLocaleString('es-MX') }}
                  </span>
                  <span class="plan-price-per">/mes MXN</span>
                </div>
              </div>
              <div class="plan-divider"></div>
              <div class="plan-specs-area">
                <div class="plan-spec">
                  <div class="spec-icon" :style="{ background: planBadgeBg(i) }">
                    <svg width="12" height="12" fill="none" :stroke="planBadgeColor(i)" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                  </div>
                  <span class="spec-val">{{ plan.max_employees >= 9999 ? 'Ilimitados' : plan.max_employees }}</span>
                  <span style="color:#9ca3af;margin-left:4px">empleados máx.</span>
                </div>
                <div class="plan-spec">
                  <div class="spec-icon" :style="{ background: planBadgeBg(i) }">
                    <svg width="12" height="12" fill="none" :stroke="planBadgeColor(i)" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                  </div>
                  <span class="spec-val">{{ plan.max_reports_per_month >= 999 ? '∞' : plan.max_reports_per_month }}</span>
                  <span style="color:#9ca3af;margin-left:4px">reportes/mes</span>
                </div>
                <div class="plan-spec">
                  <div class="spec-icon" :style="{ background: planBadgeBg(i) }">
                    <svg width="12" height="12" fill="none" :stroke="planBadgeColor(i)" stroke-width="2" viewBox="0 0 24 24"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>
                  </div>
                  <span class="spec-val">{{ plan.api_access ? 'Sí' : 'No' }}</span>
                  <span style="color:#9ca3af;margin-left:4px">API access</span>
                </div>
              </div>
              <div v-if="plan.features?.length" class="plan-feats-area">
                <span v-for="f in plan.features" :key="f" class="feat-chip">{{ f }}</span>
              </div>
              <div class="plan-foot">
                <span class="pill" :class="plan.active?'active':'inactive'">{{ plan.active?'Activo':'Inactivo' }}</span>
                <div class="icon-btns">
                  <button class="icon-btn" title="Editar plan" @click="openPlanModal(plan)">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button class="icon-btn danger" title="Desactivar plan">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                  </button>
                </div>
              </div>
            </div>
            <div v-if="!plans.length" class="empty-row" style="grid-column:1/-1">No hay planes creados.</div>
          </div>
        </div>

        <!-- ══════ SUSCRIPCIONES ══════ -->
        <div v-if="view === 'subscriptions'" class="fade-in">
          <div class="toolbar">
            <div class="sw">
              <svg class="si" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              <input v-model="subSearch" class="sf" placeholder="Buscar empresa…"/>
            </div>
            <select v-model="subFilterStatus" class="sel">
              <option value="">Todos los estados</option>
              <option value="active">Activas</option>
              <option value="trial">Trial</option>
              <option value="suspended">Suspendidas</option>
              <option value="inactive">Inactivas</option>
            </select>
          </div>
          <div class="tw">
            <div v-if="isLoading" class="loading-state"><div class="spinner"></div> Cargando…</div>
            <table v-else class="dt">
              <thead>
                <tr><th>Empresa</th><th>Plan</th><th>Estado</th><th>Inicio</th><th>Vencimiento</th><th>Trial termina</th><th class="tr">Acciones</th></tr>
              </thead>
              <tbody>
                <tr v-for="c in filteredSubs" :key="c.id" class="dr">
                  <td>
                    <div class="cell-av">
                      <div class="av" :style="{background:avatarBg(c.name)}">{{c.name?.charAt(0)}}</div>
                      <div><div class="cn">{{c.name}}</div><div class="cs">{{c.email}}</div></div>
                    </div>
                  </td>
                  <td><span class="tag">{{ planLabel(c.subscription?.plan?.slug) }}</span></td>
                  <td><span class="pill" :class="c.subscription?.status||'inactive'">{{ statusLabel(c.subscription?.status) }}</span></td>
                  <td class="cm">{{ formatDate(c.subscription?.current_period_start) }}</td>
                  <td class="cm">{{ formatDate(c.subscription?.current_period_end) }}</td>
                  <td class="cm">{{ formatDate(c.subscription?.trial_ends_at) }}</td>
                  <td>
                    <div class="icon-btns" style="justify-content:flex-end">
                      <button class="icon-btn" title="Editar" @click="openSubModal(c)">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      </button>
                      <button class="icon-btn" :class="c.subscription?.status==='active'?'danger':'success'"
                        :title="c.subscription?.status==='active'?'Suspender':'Activar'"
                        @click="toggleStatus(c)" :disabled="!c.subscription">
                        <svg v-if="c.subscription?.status==='active'" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                        <svg v-else width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="!filteredSubs.length">
                  <td colspan="7" class="empty-row">No se encontraron suscripciones.</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="tf">{{ filteredSubs.length }} de {{ companies.length }} suscripciones</div>
        </div>

        <!-- ══════ VENTAS ══════ -->
        <div v-if="view === 'ventas'" class="fade-in">

          <!-- KPIs Ventas -->
          <div class="kpi-grid" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:18px">
            <div class="kpi-card">
              <div class="kpi-hd"><span class="kpi-lbl">MRR Actual</span><div class="kpi-ico kpi-indigo"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
              <div class="kpi-trend up">▲ +4.8%</div>
              <div class="kpi-val">${{ (stats.mrr ?? 0).toLocaleString('es-MX') }}</div>
              <div class="kpi-sub kpi-sub-indigo">vs mes anterior</div>
            </div>
            <div class="kpi-card">
              <div class="kpi-hd"><span class="kpi-lbl">ARR</span><div class="kpi-ico kpi-purple"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div></div>
              <div class="kpi-trend up">▲ creciendo</div>
              <div class="kpi-val">${{ ((stats.mrr ?? 0) * 12 / 1000000).toFixed(2) }}M</div>
              <div class="kpi-sub kpi-sub-indigo">anualizado</div>
            </div>
            <div class="kpi-card">
              <div class="kpi-hd"><span class="kpi-lbl">Nuevos (mes)</span><div class="kpi-ico kpi-green"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
              <div class="kpi-trend up">▲ +4 este mes</div>
              <div class="kpi-val">{{ stats.new_this_month ?? 0 }}</div>
              <div class="kpi-sub kpi-sub-green">clientes nuevos</div>
            </div>
            <div class="kpi-card">
              <div class="kpi-hd"><span class="kpi-lbl">Churn rate</span><div class="kpi-ico kpi-red"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div></div>
              <div class="kpi-trend dn">▼ mejoró vs anterior</div>
              <div class="kpi-val">{{ stats.churn_rate ?? '0.8' }}%</div>
              <div class="kpi-sub kpi-sub-red">tasa mensual</div>
            </div>
          </div>

          <!-- Ventas Charts -->
          <div class="charts-row" style="margin-bottom:14px">
            <div class="chart-card">
              <div class="card-hd">
                <div><div class="card-title">Crecimiento MRR</div><div class="card-sub">12 meses · MXN</div></div>
              </div>
              <div style="position:relative;height:220px">
                <canvas ref="ventasMrrChart" role="img" aria-label="MRR 12 meses"></canvas>
              </div>
            </div>
            <div class="chart-card">
              <div class="card-hd">
                <div><div class="card-title">Nuevos vs Churn</div><div class="card-sub">Clientes por mes</div></div>
              </div>
              <div style="position:relative;height:220px">
                <canvas ref="ventasNvCChart" role="img" aria-label="Nuevos vs Churn"></canvas>
              </div>
            </div>
          </div>

          <div class="chart-card" style="margin-bottom:14px">
            <div class="card-hd">
              <div><div class="card-title">Ingresos por plan</div><div class="card-sub">Contribución mensual MXN</div></div>
            </div>
            <div style="position:relative;height:180px">
              <canvas ref="ventasByPlanChart" role="img" aria-label="Ingresos por plan"></canvas>
            </div>
          </div>

          <div class="chart-card">
            <div class="card-hd"><div class="card-title">Detalle de ingresos por empresa</div></div>
            <table class="mini-table">
              <thead><tr><th>Empresa</th><th>Plan</th><th>MRR</th><th>Vence</th><th>Estado</th></tr></thead>
              <tbody>
                <tr v-for="c in companies" :key="c.id">
                  <td>
                    <div class="cell-av">
                      <div class="av" :style="{background:avatarBg(c.name)}">{{c.name?.charAt(0)}}</div>
                      <div><div class="cn">{{c.name}}</div></div>
                    </div>
                  </td>
                  <td><span class="tag">{{ planLabel(c.subscription?.plan?.slug) }}</span></td>
                  <td class="num" style="color:#4f46e5;font-weight:700">${{ getPlanPrice(c.subscription?.plan?.slug).toLocaleString('es-MX') }}</td>
                  <td class="cm">{{ formatDate(c.subscription?.current_period_end) }}</td>
                  <td><span class="pill" :class="c.subscription?.status||'inactive'">{{ statusLabel(c.subscription?.status) }}</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </main>
    </div>

    <!-- ══════ MODAL EMPRESA ══════ -->
    <Teleport to="body">
      <div v-if="showCompanyModal" class="mbg" @click.self="showCompanyModal=false">
        <div class="mbox">
          <div class="mhd">
            <div>
              <div class="mt">{{ editingCompany?.id ? 'Editar empresa' : 'Nueva empresa' }}</div>
              <div class="ms">{{ editingCompany?.id ? 'Modifica los datos' : 'Registra una nueva empresa' }}</div>
            </div>
            <button class="mx" @click="showCompanyModal=false">✕</button>
          </div>
          <div class="mbd">
            <div class="fg2">
              <div class="fg"><label class="fl">Nombre *</label><input v-model="companyForm.name" class="fi" placeholder="Razón social"/></div>
              <div class="fg"><label class="fl">RFC</label><input v-model="companyForm.rfc" class="fi" placeholder="RFC123456789"/></div>
              <div class="fg"><label class="fl">Email *</label><input v-model="companyForm.email" type="email" class="fi" placeholder="contacto@empresa.com"/></div>
              <div class="fg"><label class="fl">Teléfono</label><input v-model="companyForm.phone" class="fi" placeholder="55 1234 5678"/></div>
              <div class="fg"><label class="fl">Plan *</label>
                <select v-model="companyForm.plan_id" class="fi">
                  <option value="">Seleccionar plan</option>
                  <option v-for="p in plans" :key="p.id" :value="p.id">{{ p.name }} — ${{ p.price }}/mes</option>
                </select>
              </div>
              <div class="fg"><label class="fl">Estado *</label>
                <select v-model="companyForm.status" class="fi">
                  <option value="trial">Trial</option>
                  <option value="active">Activa</option>
                  <option value="suspended">Suspendida</option>
                </select>
              </div>
              <div class="fg fg-full"><label class="fl">Industria</label><input v-model="companyForm.industry" class="fi" placeholder="Manufactura, Servicios, Tecnología…"/></div>
            </div>
            <div v-if="modalError" class="merr">{{ modalError }}</div>
          </div>
          <div class="mft">
            <button class="btn-ghost" @click="showCompanyModal=false">Cancelar</button>
            <button class="btn-dark" @click="saveCompany" :disabled="isSaving">
              {{ isSaving ? 'Guardando…' : (editingCompany?.id ? 'Guardar cambios' : 'Crear empresa') }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- ══════ MODAL PLAN ══════ -->
    <Teleport to="body">
      <div v-if="showPlanModal" class="mbg" @click.self="showPlanModal=false">
        <div class="mbox">
          <div class="mhd">
            <div>
              <div class="mt">{{ editingPlan?.id ? 'Editar plan' : 'Nuevo plan' }}</div>
              <div class="ms">Configura precio, límites y características</div>
            </div>
            <button class="mx" @click="showPlanModal=false">✕</button>
          </div>
          <div class="mbd">
            <div class="fg2">
              <div class="fg"><label class="fl">Nombre *</label><input v-model="planForm.name" class="fi" placeholder="Plan Básico"/></div>
              <div class="fg"><label class="fl">Slug *</label><input v-model="planForm.slug" class="fi" placeholder="basico"/></div>
              <div class="fg"><label class="fl">Precio MXN/mes *</label><input v-model.number="planForm.price" type="number" min="0" class="fi" placeholder="999"/></div>
              <div class="fg"><label class="fl">Máx. empleados *</label><input v-model.number="planForm.max_employees" type="number" min="1" class="fi" placeholder="50"/></div>
              <div class="fg"><label class="fl">Reportes/mes *</label><input v-model.number="planForm.max_reports_per_month" type="number" min="0" class="fi" placeholder="10"/></div>
              <div class="fg"><label class="fl">API Access</label>
                <select v-model="planForm.api_access" class="fi">
                  <option :value="false">No</option>
                  <option :value="true">Sí</option>
                </select>
              </div>
              <div class="fg"><label class="fl">Estado</label>
                <select v-model="planForm.active" class="fi">
                  <option :value="true">Activo</option>
                  <option :value="false">Inactivo</option>
                </select>
              </div>
              <div class="fg fg-full">
                <label class="fl">Features <span style="font-weight:400;color:#9ca3af">(una por línea)</span></label>
                <textarea v-model="planFeaturesText" class="fi fi-ta" rows="4" placeholder="Encuestas ilimitadas&#10;Soporte prioritario&#10;Exportar PDF"></textarea>
              </div>
            </div>
            <div v-if="modalError" class="merr">{{ modalError }}</div>
          </div>
          <div class="mft">
            <button class="btn-ghost" @click="showPlanModal=false">Cancelar</button>
            <button class="btn-dark" @click="savePlan" :disabled="isSaving">
              {{ isSaving ? 'Guardando…' : (editingPlan?.id ? 'Guardar cambios' : 'Crear plan') }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- ══════ MODAL SUSCRIPCIÓN ══════ -->
    <Teleport to="body">
      <div v-if="showSubModal" class="mbg" @click.self="showSubModal=false">
        <div class="mbox mbox-sm">
          <div class="mhd">
            <div>
              <div class="mt">Editar suscripción</div>
              <div class="ms">{{ editingSub?.name }}</div>
            </div>
            <button class="mx" @click="showSubModal=false">✕</button>
          </div>
          <div class="mbd">
            <div class="fg2">
              <div class="fg fg-full"><label class="fl">Plan</label>
                <select v-model="subForm.plan_id" class="fi">
                  <option v-for="p in plans" :key="p.id" :value="p.id">{{ p.name }} — ${{ p.price }}/mes</option>
                </select>
              </div>
              <div class="fg fg-full"><label class="fl">Estado</label>
                <select v-model="subForm.status" class="fi">
                  <option value="trial">Trial</option>
                  <option value="active">Activa</option>
                  <option value="suspended">Suspendida</option>
                </select>
              </div>
            </div>
            <div v-if="modalError" class="merr">{{ modalError }}</div>
          </div>
          <div class="mft">
            <button class="btn-ghost" @click="showSubModal=false">Cancelar</button>
            <button class="btn-dark" @click="saveSub" :disabled="isSaving">
              {{ isSaving ? 'Guardando…' : 'Guardar cambios' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue'

// ─── Chart.js (asegúrate de instalar: npm install chart.js) ───────────────────
import {
  Chart,
  BarController, LineController, DoughnutController,
  BarElement, LineElement, PointElement, ArcElement,
  CategoryScale, LinearScale,
  Tooltip, Legend
} from 'chart.js'

Chart.register(
  BarController, LineController, DoughnutController,
  BarElement, LineElement, PointElement, ArcElement,
  CategoryScale, LinearScale,
  Tooltip, Legend
)

// ─── Estado global ────────────────────────────────────────────────────────────
const adminName  = ref('Super Admin')
const adminEmail = ref('admin@gioncorey.com')
const view       = ref('dashboard')
const networkError = ref('')
const isLoading  = ref(false)
const isSaving   = ref(false)
const modalError = ref('')
const chartType  = ref('bar')

const companies = ref([])
const plans     = ref([])
const stats     = ref({})

const search         = ref('')
const filterStatus   = ref('')
const subSearch      = ref('')
const subFilterStatus = ref('')

const showCompanyModal = ref(false)
const showPlanModal    = ref(false)
const showSubModal     = ref(false)
const editingCompany   = ref(null)
const editingPlan      = ref(null)
const editingSub       = ref(null)
const planFeaturesText = ref('')

const companyForm = ref({ name:'', rfc:'', email:'', phone:'', plan_id:'', status:'trial', industry:'' })
const planForm    = ref({ name:'', slug:'', price:0, max_employees:10, max_reports_per_month:5, api_access:false, active:true, features:[] })
const subForm     = ref({ plan_id:'', status:'active' })

// Chart refs
const mrrChart         = ref(null)
const donutChart       = ref(null)
const ventasMrrChart   = ref(null)
const ventasNvCChart   = ref(null)
const ventasByPlanChart = ref(null)

let mrrInstance      = null
let donutInstance    = null
let v_mrrInstance    = null
let v_nvcInstance    = null
let v_planInstance   = null

// ─── Datos de gráficas ────────────────────────────────────────────────────────
const mrrLabels  = ['May','Jun','Jul','Ago','Sep','Oct','Nov','Dic','Ene','Feb','Mar','Abr']
const mrrData    = [78000,82000,79000,91000,88000,97000,104000,99000,112000,118000,125000,131000]
const newClients = [3,2,4,3,5,4,6,3,4,5,3,4]
const churnData  = [1,1,0,1,1,0,1,2,0,1,0,1]

// ─── Íconos nav ───────────────────────────────────────────────────────────────
const icons = {
  dashboard:    `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>`,
  companies:    `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`,
  plans:        `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`,
  subscriptions:`<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>`,
  ventas:       `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>`,
}

// ─── Nav items ────────────────────────────────────────────────────────────────
const navItems = computed(() => [
  { id:'dashboard',     label:'Dashboard',      icon:icons.dashboard,     badge: null },
  { id:'companies',     label:'Empresas',       icon:icons.companies,     badge: companies.value.length || null },
  { id:'plans',         label:'Planes',         icon:icons.plans,         badge: plans.value.length || null },
  { id:'subscriptions', label:'Suscripciones',  icon:icons.subscriptions, badge: null },
  { id:'ventas',        label:'Ventas',         icon:icons.ventas,        badge: null },
])

const currentItem = computed(() => navItems.value.find(n => n.id === view.value))

// ─── Filtros ──────────────────────────────────────────────────────────────────
const filteredCompanies = computed(() => companies.value.filter(c => {
  const q = search.value.toLowerCase()
  return (!q || c.name?.toLowerCase().includes(q) || c.rfc?.toLowerCase().includes(q) || c.email?.toLowerCase().includes(q))
    && (!filterStatus.value || c.subscription?.status === filterStatus.value)
}))

const filteredSubs = computed(() => companies.value.filter(c => {
  const q = subSearch.value.toLowerCase()
  return (!q || c.name?.toLowerCase().includes(q) || c.email?.toLowerCase().includes(q))
    && (!subFilterStatus.value || c.subscription?.status === subFilterStatus.value)
}))

// ─── Helpers ──────────────────────────────────────────────────────────────────
const planLabel  = s => ({ basico:'Básico', profesional:'Profesional', empresarial:'Empresarial' }[s?.toLowerCase()] || s || 'Sin plan')
const statusLabel = s => ({ active:'Activa', suspended:'Suspendida', trial:'Trial', inactive:'Inactiva' }[s] || 'Sin suscripción')
const formatDate  = d => d ? new Date(d).toLocaleDateString('es-MX') : '—'
const getPlanColor = slug => ({ basico:'#6366F1', profesional:'#0EA5E9', empresarial:'#10B981' }[slug] || '#64748B')
const getPlanPrice = slug => plans.value.find(p => p.slug === slug)?.price || 0
const avatarBg = name => {
  const c = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899']
  return c[(name?.charCodeAt(0) || 0) % c.length]
}

const planHeroGradient = i => ['linear-gradient(135deg,#EEF2FF,#E0E7FF)','linear-gradient(135deg,#E0F2FE,#BAE6FD)','linear-gradient(135deg,#ECFDF5,#A7F3D0)'][i] || 'linear-gradient(135deg,#F9FAFB,#F3F4F6)'
const planBadgeBg    = i => ['#EEF2FF','#E0F2FE','#ECFDF5'][i] || '#F4F2EF'
const planBadgeColor = i => ['#4F46E5','#0369A1','#065F46'][i] || '#374151'

const token = () => localStorage.getItem('admin_token')
function goToLogin() { localStorage.removeItem('admin_token'); localStorage.removeItem('admin_user'); window.location.href = '/admin/login' }

// ─── API helper ───────────────────────────────────────────────────────────────
async function api(url, opts = {}) {
  let res
  try {
    res = await fetch(url, {
      ...opts,
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${token()}`,
        'Content-Type': 'application/json',
        ...(opts.headers || {})
      }
    })
  } catch (e) { networkError.value = '❌ Sin conexión'; throw e }
  if (res.status === 401 || res.status === 403) { goToLogin(); throw new Error('No autorizado') }
  if (!res.ok) { const t = await res.text(); networkError.value = `Error ${res.status}`; throw new Error(t) }
  networkError.value = ''
  return res.json()
}

// ─── Carga inicial ────────────────────────────────────────────────────────────
async function loadAll() {
  isLoading.value = true
  try {
    const [comp, pl, dash] = await Promise.all([
      api('/api/admin/companies'),
      api('/api/admin/plans'),
      api('/api/admin/dashboard')
    ])
    companies.value = Array.isArray(comp?.data) ? comp.data : Array.isArray(comp) ? comp : []
    plans.value     = Array.isArray(pl) ? pl : []
    stats.value     = dash || {}
  } catch (e) { console.error(e) } finally { isLoading.value = false }
}

// ─── Acciones empresa ─────────────────────────────────────────────────────────
async function toggleStatus(company) {
  if (!company.subscription) return
  try {
    const d = await api(`/api/admin/companies/${company.id}/toggle-status`, { method: 'PATCH' })
    company.subscription.status = d.status
  } catch (e) { console.error(e) }
}

async function deleteCompany(company) {
  if (!confirm(`¿Eliminar "${company.name}"?`)) return
  try {
    await api(`/api/admin/companies/${company.id}`, { method: 'DELETE' })
    companies.value = companies.value.filter(c => c.id !== company.id)
  } catch (e) { console.error(e) }
}

function openCompanyModal(company = null) {
  editingCompany.value = company; modalError.value = ''
  companyForm.value = company
    ? { name: company.name||'', rfc: company.rfc||'', email: company.email||'', phone: company.phone||'', plan_id: company.subscription?.plan_id||'', status: company.subscription?.status||'trial', industry: company.industry||'' }
    : { name:'', rfc:'', email:'', phone:'', plan_id: plans.value[0]?.id||'', status:'trial', industry:'' }
  showCompanyModal.value = true
}

async function saveCompany() {
  modalError.value = ''
  if (!companyForm.value.name || !companyForm.value.email) { modalError.value = 'Nombre y email son requeridos.'; return }
  isSaving.value = true
  try {
    let r
    if (editingCompany.value?.id) {
      r = await api(`/api/admin/companies/${editingCompany.value.id}`, { method:'PUT', body: JSON.stringify(companyForm.value) })
      const i = companies.value.findIndex(c => c.id === r.id)
      if (i !== -1) companies.value[i] = r
    } else {
      r = await api('/api/admin/companies', { method:'POST', body: JSON.stringify(companyForm.value) })
      companies.value.unshift(r)
    }
    showCompanyModal.value = false
  } catch (e) { modalError.value = 'Error al guardar. Verifica los datos.' } finally { isSaving.value = false }
}

// ─── Acciones plan ────────────────────────────────────────────────────────────
function openPlanModal(plan = null) {
  editingPlan.value = plan; modalError.value = ''
  if (plan) {
    planForm.value = { name: plan.name, slug: plan.slug, price: plan.price, max_employees: plan.max_employees, max_reports_per_month: plan.max_reports_per_month, api_access: !!plan.api_access, active: !!plan.active, features: plan.features || [] }
    planFeaturesText.value = (plan.features || []).join('\n')
  } else {
    planForm.value = { name:'', slug:'', price:0, max_employees:10, max_reports_per_month:5, api_access:false, active:true, features:[] }
    planFeaturesText.value = ''
  }
  showPlanModal.value = true
}

async function savePlan() {
  modalError.value = ''
  if (!planForm.value.name || !planForm.value.slug) { modalError.value = 'Nombre y slug requeridos.'; return }
  planForm.value.features = planFeaturesText.value.split('\n').map(s => s.trim()).filter(Boolean)
  isSaving.value = true
  try {
    let r
    if (editingPlan.value?.id) {
      r = await api(`/api/admin/plans/${editingPlan.value.id}`, { method:'PUT', body: JSON.stringify(planForm.value) })
      const i = plans.value.findIndex(p => p.id === r.id)
      if (i !== -1) plans.value[i] = r
    } else {
      r = await api('/api/admin/plans', { method:'POST', body: JSON.stringify(planForm.value) })
      plans.value.push(r)
    }
    showPlanModal.value = false
  } catch (e) { modalError.value = 'Error al guardar el plan.' } finally { isSaving.value = false }
}

// ─── Acciones suscripción ─────────────────────────────────────────────────────
function openSubModal(company) {
  editingSub.value = company; modalError.value = ''
  subForm.value = { plan_id: company.subscription?.plan_id || plans.value[0]?.id, status: company.subscription?.status || 'trial' }
  showSubModal.value = true
}

async function saveSub() {
  modalError.value = ''; isSaving.value = true
  try {
    const r = await api(`/api/admin/companies/${editingSub.value.id}`, { method:'PUT', body: JSON.stringify(subForm.value) })
    const i = companies.value.findIndex(c => c.id === r.id)
    if (i !== -1) companies.value[i] = r
    showSubModal.value = false
  } catch (e) { modalError.value = 'Error al guardar la suscripción.' } finally { isSaving.value = false }
}

// ─── Logout ───────────────────────────────────────────────────────────────────
async function logout() {
  try { await api('/api/admin/logout', { method: 'POST' }) } catch {}
  goToLogin()
}

// ─── Charts ───────────────────────────────────────────────────────────────────
function destroyCharts() {
  [mrrInstance, donutInstance, v_mrrInstance, v_nvcInstance, v_planInstance].forEach(c => c?.destroy())
  mrrInstance = donutInstance = v_mrrInstance = v_nvcInstance = v_planInstance = null
}

async function initDashCharts() {
  await nextTick()
  if (mrrChart.value) {
    mrrInstance = new Chart(mrrChart.value, {
      type: chartType.value === 'line' ? 'line' : 'bar',
      data: {
        labels: mrrLabels,
        datasets: [{
          label: 'MRR',
          data: mrrData,
          backgroundColor: chartType.value === 'line' ? 'rgba(99,102,241,0.08)' : 'rgba(99,102,241,0.75)',
          borderColor: '#6366F1',
          borderWidth: chartType.value === 'line' ? 2 : 0,
          borderRadius: chartType.value === 'bar' ? 6 : 0,
          fill: chartType.value === 'line',
          tension: 0.4,
          pointRadius: chartType.value === 'line' ? 3 : 0,
          pointBackgroundColor: '#6366F1',
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: v => `$${v.raw.toLocaleString('es-MX')}` } } },
        scales: {
          x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#9ca3af' } },
          y: { grid: { color: '#F4F2EF' }, ticks: { callback: v => `$${(v/1000).toFixed(0)}K`, font: { size: 10 }, color: '#9ca3af' } }
        }
      }
    })
  }
  if (donutChart.value) {
    donutInstance = new Chart(donutChart.value, {
      type: 'doughnut',
      data: {
        labels: ['Empresarial','Profesional','Básico'],
        datasets: [{ data: [44,34,22], backgroundColor: ['#10B981','#0EA5E9','#6366F1'], borderWidth: 0, hoverOffset: 4 }]
      },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '68%' }
    })
  }
}

async function initVentasCharts() {
  await nextTick()
  if (ventasMrrChart.value) {
    v_mrrInstance = new Chart(ventasMrrChart.value, {
      type: 'line',
      data: { labels: mrrLabels, datasets: [{ label:'MRR', data: mrrData, borderColor:'#6366F1', backgroundColor:'rgba(99,102,241,0.08)', borderWidth:2, fill:true, tension:0.4, pointRadius:3, pointBackgroundColor:'#6366F1' }] },
      options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:{ callbacks:{label:v=>`$${v.raw.toLocaleString('es-MX')}`} } }, scales:{ x:{grid:{display:false},ticks:{font:{size:10},color:'#9ca3af'}}, y:{grid:{color:'#F4F2EF'},ticks:{callback:v=>`$${(v/1000).toFixed(0)}K`,font:{size:10},color:'#9ca3af'}} } }
    })
  }
  if (ventasNvCChart.value) {
    v_nvcInstance = new Chart(ventasNvCChart.value, {
      type: 'bar',
      data: { labels: mrrLabels, datasets: [
        { label:'Nuevos', data: newClients, backgroundColor:'rgba(16,185,129,0.75)', borderRadius:5, borderSkipped:false },
        { label:'Churn',  data: churnData.map(v => -v), backgroundColor:'rgba(239,68,68,0.65)', borderRadius:5, borderSkipped:false }
      ]},
      options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:{callbacks:{label:v=>`${v.dataset.label}: ${Math.abs(v.raw)}`}} }, scales:{ x:{grid:{display:false},ticks:{font:{size:10},color:'#9ca3af'},stacked:true}, y:{grid:{color:'#F4F2EF'},ticks:{font:{size:10},color:'#9ca3af'},stacked:true} } }
    })
  }
  if (ventasByPlanChart.value) {
    v_planInstance = new Chart(ventasByPlanChart.value, {
      type: 'bar',
      data: {
        labels: ['Empresarial','Profesional','Básico'],
        datasets: [{ data: [8999*4, 3999*3, 1499*2], backgroundColor: ['rgba(16,185,129,0.75)','rgba(14,165,233,0.75)','rgba(99,102,241,0.75)'], borderRadius:8, borderSkipped:false }]
      },
      options: { indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:{callbacks:{label:v=>`$${v.raw.toLocaleString('es-MX')}`}} }, scales:{ x:{grid:{color:'#F4F2EF'},ticks:{callback:v=>`$${(v/1000).toFixed(0)}K`,font:{size:10},color:'#9ca3af'}}, y:{grid:{display:false},ticks:{font:{size:10,weight:'500'},color:'#374151'}} } }
    })
  }
}

// ─── Watchers ─────────────────────────────────────────────────────────────────
watch(view, async newView => {
  destroyCharts()
  await nextTick()
  if (newView === 'dashboard') initDashCharts()
  if (newView === 'ventas')    initVentasCharts()
})

watch(chartType, async () => {
  mrrInstance?.destroy(); mrrInstance = null
  await nextTick()
  initDashCharts()
})

// ─── Mounted ──────────────────────────────────────────────────────────────────
onMounted(async () => {
  if (!token()) { goToLogin(); return }
  const saved = localStorage.getItem('admin_user')
  if (saved) {
    try { const u = JSON.parse(saved); adminName.value = u.name || adminName.value; adminEmail.value = u.email || adminEmail.value } catch {}
  }
  await loadAll()
  await nextTick()
  initDashCharts()
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&family=DM+Mono:wght@400;500&display=swap');
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.shell { display: flex; min-height: 100vh; background: #F4F2EF; font-family: 'DM Sans', system-ui, sans-serif; font-size: 14px; color: #1a1a1a; }

/* ── SIDEBAR ─────────────────────────────────────────────────────────────── */
.sidebar { width: 232px; background: #fff; border-right: 1px solid #E8E5E0; display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; flex-shrink: 0; }
.sidebar-top { display: flex; flex-direction: column; flex: 1; overflow-y: auto; }
.brand-wrap { display: flex; align-items: center; gap: 11px; padding: 20px 18px 16px; border-bottom: 1px solid #EDEAE5; }
.brand-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(79,70,229,.28); }
.brand-name { font-size: 14px; font-weight: 700; color: #111; letter-spacing: -0.01em; }
.brand-role { font-size: 10px; color: #9ca3af; margin-top: 1px; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 500; }
.nav { padding: 12px 10px; flex: 1; }
.nav-section-label { font-size: 10px; font-weight: 600; color: #c4bfb8; letter-spacing: 0.1em; padding: 0 8px 8px; text-transform: uppercase; }
.nav-item { display: flex; align-items: center; gap: 9px; width: 100%; padding: 8px 10px; border: none; background: none; border-radius: 9px; color: #6b7280; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.13s; margin-bottom: 2px; text-align: left; font-family: inherit; }
.nav-item:hover { background: #F4F2EF; color: #374151; }
.nav-item.active { background: #EEF2FF; color: #4F46E5; }
.nav-icon { display: flex; align-items: center; flex-shrink: 0; width: 16px; height: 16px; }
.nav-label { flex: 1; }
.nav-badge { background: #F4F2EF; color: #9ca3af; font-size: 11px; padding: 1px 7px; border-radius: 99px; font-family: 'DM Mono', monospace; }
.nav-item.active .nav-badge { background: #E0E7FF; color: #4F46E5; }
.sidebar-bottom { padding: 14px 10px; border-top: 1px solid #EDEAE5; }
.admin-chip { display: flex; align-items: center; gap: 9px; padding: 9px 10px; border-radius: 9px; background: #F9F8F6; }
.admin-avatar { width: 30px; height: 30px; background: linear-gradient(135deg,#4F46E5,#7C3AED); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; color: #fff; flex-shrink: 0; }
.admin-info { flex: 1; min-width: 0; }
.admin-name { font-size: 12.5px; font-weight: 600; color: #111; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.admin-email { font-size: 11px; color: #9ca3af; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.logout-icon { background: none; border: none; cursor: pointer; color: #d1d5db; padding: 4px; border-radius: 6px; display: flex; align-items: center; transition: color 0.13s; flex-shrink: 0; }
.logout-icon:hover { color: #ef4444; }

/* ── MAIN ────────────────────────────────────────────────────────────────── */
.main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
.topbar { height: 56px; background: #fff; border-bottom: 1px solid #E8E5E0; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 20; }
.breadcrumb { display: flex; align-items: center; gap: 5px; margin-bottom: 1px; }
.bc-root { font-size: 11px; color: #9ca3af; }
.bc-sep  { font-size: 11px; color: #d1d5db; }
.bc-cur  { font-size: 11px; color: #6b7280; font-weight: 500; }
.page-title { font-size: 15px; font-weight: 700; color: #111; letter-spacing: -0.01em; }
.topbar-right { display: flex; align-items: center; gap: 10px; }
.env-pill { font-size: 11.5px; color: #16a34a; font-weight: 600; background: #f0fdf4; padding: 4px 11px; border-radius: 99px; border: 1px solid #bbf7d0; }
.error-chip { font-size: 12px; color: #b91c1c; background: #fef2f2; padding: 4px 11px; border-radius: 99px; border: 1px solid #fca5a5; }
.content { flex: 1; padding: 20px 24px; overflow-y: auto; }

.fade-in { animation: fi 0.18s ease; }
@keyframes fi { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: none; } }
.loading-state { display: flex; align-items: center; gap: 10px; color: #6b7280; padding: 48px; font-size: 14px; }
.spinner { width: 17px; height: 17px; border: 2px solid #e5e7eb; border-top-color: #4f46e5; border-radius: 50%; animation: spin 0.7s linear infinite; flex-shrink: 0; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── KPI ─────────────────────────────────────────────────────────────────── */
.kpi-grid { display: grid; grid-template-columns: repeat(6, minmax(0,1fr)); gap: 10px; margin-bottom: 16px; }
.kpi-card { background: #fff; border-radius: 13px; border: 1px solid #E8E5E0; padding: 16px 17px; transition: box-shadow 0.15s; }
.kpi-card:hover { box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.kpi-hd { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
.kpi-lbl { font-size: 10.5px; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.kpi-ico { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.kpi-green  { background: #f0fdf4; color: #16a34a; }
.kpi-amber  { background: #fffbeb; color: #d97706; }
.kpi-red    { background: #fef2f2; color: #dc2626; }
.kpi-indigo { background: #eef2ff; color: #4f46e5; }
.kpi-blue   { background: #eff6ff; color: #2563eb; }
.kpi-purple { background: #faf5ff; color: #7c3aed; }
.kpi-trend { display: inline-flex; align-items: center; gap: 3px; font-size: 10.5px; font-weight: 600; padding: 2px 7px; border-radius: 99px; margin-bottom: 5px; }
.up { background: #f0fdf4; color: #16a34a; }
.dn { background: #fef2f2; color: #dc2626; }
.kpi-val { font-size: 21px; font-weight: 700; color: #111; letter-spacing: -0.03em; line-height: 1; margin-bottom: 5px; font-family: 'DM Mono', monospace; }
.kpi-sub { font-size: 11px; color: #9ca3af; font-weight: 500; }
.kpi-sub-green  { color: #16a34a; }
.kpi-sub-amber  { color: #d97706; }
.kpi-sub-red    { color: #dc2626; }
.kpi-sub-indigo { color: #4f46e5; }

/* ── CHARTS ──────────────────────────────────────────────────────────────── */
.charts-row { display: grid; grid-template-columns: 2fr 1fr; gap: 12px; margin-bottom: 14px; }
.chart-card { background: #fff; border-radius: 13px; border: 1px solid #E8E5E0; padding: 18px 20px; }
.card-hd { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.card-title { font-size: 13px; font-weight: 700; color: #111; }
.card-sub { font-size: 11.5px; color: #9ca3af; margin-top: 2px; }
.tabs { display: flex; gap: 2px; background: #F4F2EF; padding: 3px; border-radius: 8px; }
.tab { padding: 4px 11px; font-size: 11.5px; font-weight: 500; border: none; background: none; border-radius: 6px; cursor: pointer; color: #6b7280; font-family: inherit; transition: all 0.13s; }
.tab.active { background: #fff; color: #111; box-shadow: 0 1px 3px rgba(0,0,0,.08); }

/* ── DIST ────────────────────────────────────────────────────────────────── */
.dist-list { display: flex; flex-direction: column; gap: 13px; }
.dist-row { display: flex; align-items: center; gap: 9px; }
.dist-meta { display: flex; align-items: center; gap: 7px; width: 105px; flex-shrink: 0; }
.dist-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.dist-name { font-size: 12px; color: #374151; font-weight: 500; }
.dist-track { flex: 1; height: 5px; background: #F4F2EF; border-radius: 99px; overflow: hidden; }
.dist-bar { height: 100%; border-radius: 99px; min-width: 4px; }
.dist-right { display: flex; flex-direction: column; align-items: flex-end; width: 60px; flex-shrink: 0; }
.dist-pct { font-size: 12px; font-weight: 700; color: #111; font-family: 'DM Mono', monospace; }
.dist-cnt { font-size: 10.5px; color: #9ca3af; }

/* ── BOTTOM ROW ──────────────────────────────────────────────────────────── */
.bottom-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

/* ── ACTIVITY ────────────────────────────────────────────────────────────── */
.activity-feed { display: flex; flex-direction: column; gap: 14px; }
.act-row { display: flex; gap: 10px; align-items: flex-start; }
.act-bullet { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
.b-new     { background: #10b981; }
.b-suspend { background: #ef4444; }
.b-renew   { background: #6366f1; }
.act-text { font-size: 12.5px; color: #374151; line-height: 1.5; }
.act-time { font-size: 11px; color: #9ca3af; margin-top: 1px; }
.empty-mini { color: #9ca3af; font-size: 13px; text-align: center; padding: 18px 0; }

/* ── TOOLBAR ─────────────────────────────────────────────────────────────── */
.toolbar { display: flex; gap: 9px; margin-bottom: 14px; align-items: center; flex-wrap: wrap; }
.sw { flex: 1; min-width: 200px; position: relative; }
.si { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; }
.sf { width: 100%; padding: 8px 11px 8px 33px; border: 1px solid #E8E5E0; border-radius: 9px; font-size: 13px; background: #fff; outline: none; font-family: inherit; color: #111; transition: border-color 0.13s; }
.sf:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }
.sel { padding: 8px 11px; border: 1px solid #E8E5E0; border-radius: 9px; font-size: 13px; background: #fff; font-family: inherit; color: #374151; cursor: pointer; outline: none; }
.btn-dark { display: flex; align-items: center; gap: 6px; padding: 8px 14px; background: #111; color: #fff; border: none; border-radius: 9px; font-size: 12.5px; font-weight: 600; cursor: pointer; white-space: nowrap; font-family: inherit; transition: background 0.13s; }
.btn-dark:hover { background: #333; }
.btn-dark:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-ghost { padding: 8px 14px; background: #f9fafb; color: #374151; border: 1px solid #E8E5E0; border-radius: 9px; font-size: 12.5px; font-weight: 500; cursor: pointer; font-family: inherit; transition: background 0.13s; }
.btn-ghost:hover { background: #f3f4f6; }

/* ── ICON BUTTONS ────────────────────────────────────────────────────────── */
.icon-btn { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 1px solid #E8E5E0; border-radius: 8px; background: #fff; cursor: pointer; color: #6b7280; transition: all 0.13s; flex-shrink: 0; }
.icon-btn:hover { background: #F4F2EF; border-color: #d1d5db; color: #374151; }
.icon-btn.danger:hover  { background: #fef2f2; border-color: #fca5a5; color: #dc2626; }
.icon-btn.success:hover { background: #f0fdf4; border-color: #86efac; color: #16a34a; }
.icon-btn:disabled { opacity: 0.35; cursor: not-allowed; }
.icon-btns { display: flex; align-items: center; gap: 5px; }

/* ── TABLE ───────────────────────────────────────────────────────────────── */
.tw { background: #fff; border-radius: 13px; border: 1px solid #E8E5E0; overflow: hidden; }
.dt { width: 100%; border-collapse: collapse; }
.dt th { padding: 10px 14px; background: #FAFAF8; text-align: left; font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.07em; border-bottom: 1px solid #EDEAE5; }
.dt td { padding: 12px 14px; border-bottom: 1px solid #F4F2EF; vertical-align: middle; }
.dt tbody tr:last-child td { border-bottom: none; }
.dt tbody tr:hover td { background: #FAFAF8; }
.tr { text-align: right; }
.cell-av { display: flex; align-items: center; gap: 9px; }
.av { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 12.5px; font-weight: 700; color: #fff; flex-shrink: 0; }
.cn { font-size: 12.5px; font-weight: 600; color: #111; }
.cs { font-size: 11px; color: #9ca3af; margin-top: 1px; }
.cm { font-size: 12.5px; color: #6b7280; }
.num { font-size: 12.5px; color: #374151; font-family: 'DM Mono', monospace; }
.tag { display: inline-block; background: #F4F2EF; color: #374151; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; }
.pill { display: inline-block; padding: 3px 9px; border-radius: 99px; font-size: 11px; font-weight: 600; white-space: nowrap; }
.pill.active    { background: #f0fdf4; color: #16a34a; }
.pill.suspended { background: #fef2f2; color: #dc2626; }
.pill.trial     { background: #fffbeb; color: #d97706; }
.pill.inactive  { background: #f9fafb; color: #6b7280; }
.tf { padding: 9px 14px; font-size: 11px; color: #9ca3af; text-align: right; }
.empty-row { text-align: center; color: #9ca3af; padding: 48px; font-size: 13px; }
.link-btn { background: none; border: none; font-size: 12px; color: #4f46e5; cursor: pointer; font-family: inherit; font-weight: 600; padding: 0; }
.link-btn:hover { text-decoration: underline; }

/* ── MINI TABLE ──────────────────────────────────────────────────────────── */
.mini-table { width: 100%; border-collapse: collapse; }
.mini-table th { padding: 8px 12px; background: #FAFAF8; text-align: left; font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.07em; border-bottom: 1px solid #EDEAE5; }
.mini-table td { padding: 10px 12px; border-bottom: 1px solid #F4F2EF; vertical-align: middle; font-size: 12.5px; }
.mini-table tr:last-child td { border-bottom: none; }
.mini-table tr:hover td { background: #FAFAF8; }

/* ── PLANS ───────────────────────────────────────────────────────────────── */
.plans-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px,1fr)); gap: 14px; padding-top: 4px; }
.plan-card { background: #fff; border-radius: 16px; border: 1px solid #E8E5E0; overflow: hidden; display: flex; flex-direction: column; transition: all 0.2s; }
.plan-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.08); transform: translateY(-2px); }
.plan-off { opacity: 0.55; }
.plan-hero { padding: 22px 22px 18px; position: relative; overflow: hidden; }
.plan-badge { display: inline-block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; padding: 3px 9px; border-radius: 99px; margin-bottom: 12px; }
.plan-nm { font-size: 20px; font-weight: 700; color: #111; letter-spacing: -0.02em; }
.plan-slug-txt { font-size: 11px; color: #9ca3af; font-family: 'DM Mono', monospace; margin-top: 2px; }
.plan-price-row { display: flex; align-items: baseline; gap: 4px; margin-top: 14px; }
.plan-price-big { font-size: 30px; font-weight: 700; letter-spacing: -0.03em; font-family: 'DM Mono', monospace; }
.plan-price-per { font-size: 12px; color: #9ca3af; margin-left: 2px; }
.plan-divider { height: 1px; background: #EDEAE5; margin: 0 22px; }
.plan-specs-area { padding: 16px 22px 14px; }
.plan-spec { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #374151; margin-bottom: 10px; }
.plan-spec:last-child { margin-bottom: 0; }
.spec-icon { width: 22px; height: 22px; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.spec-val { font-weight: 700; color: #111; font-family: 'DM Mono', monospace; }
.plan-feats-area { padding: 0 22px 16px; display: flex; flex-wrap: wrap; gap: 5px; }
.feat-chip { font-size: 11px; background: #F4F2EF; color: #6b7280; padding: 3px 9px; border-radius: 6px; font-weight: 500; }
.plan-foot { padding: 12px 22px; border-top: 1px solid #F4F2EF; display: flex; align-items: center; justify-content: space-between; background: #FAFAF8; margin-top: auto; }

/* ── MODALS ──────────────────────────────────────────────────────────────── */
.mbg { position: fixed; inset: 0; background: rgba(0,0,0,.42); display: flex; align-items: center; justify-content: center; z-index: 200; padding: 24px; backdrop-filter: blur(3px); }
.mbox { background: #fff; border-radius: 16px; width: 100%; max-width: 560px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 20px 50px rgba(0,0,0,.18); overflow: hidden; }
.mbox-sm { max-width: 420px; }
.mhd { display: flex; align-items: flex-start; justify-content: space-between; padding: 20px 22px 16px; border-bottom: 1px solid #F4F2EF; flex-shrink: 0; }
.mt { font-size: 15px; font-weight: 700; color: #111; }
.ms { font-size: 12.5px; color: #6b7280; margin-top: 3px; }
.mx { background: none; border: none; cursor: pointer; color: #9ca3af; font-size: 17px; padding: 2px 6px; border-radius: 5px; line-height: 1; transition: all 0.1s; }
.mx:hover { background: #fef2f2; color: #dc2626; }
.mbd { padding: 18px 22px; overflow-y: auto; flex: 1; }
.mft { padding: 14px 22px; border-top: 1px solid #F4F2EF; display: flex; justify-content: flex-end; gap: 9px; flex-shrink: 0; background: #FAFAF8; }
.fg2 { display: grid; grid-template-columns: 1fr 1fr; gap: 13px; }
.fg { display: flex; flex-direction: column; gap: 5px; }
.fg-full { grid-column: 1 / -1; }
.fl { font-size: 11.5px; font-weight: 600; color: #374151; letter-spacing: 0.01em; }
.fi { padding: 8px 11px; border: 1px solid #E8E5E0; border-radius: 8px; font-size: 13px; font-family: inherit; color: #111; background: #fff; outline: none; transition: border-color 0.13s; }
.fi:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }
.fi-ta { resize: vertical; min-height: 88px; }
.merr { margin-top: 13px; padding: 9px 13px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; color: #b91c1c; font-size: 12.5px; }

/* ── RESPONSIVE ──────────────────────────────────────────────────────────── */
@media (max-width: 1280px) { .kpi-grid { grid-template-columns: repeat(3, minmax(0,1fr)); } }
@media (max-width: 1024px) { .charts-row, .bottom-row { grid-template-columns: 1fr; } }
@media (max-width: 900px)  { .sidebar { display: none; } .kpi-grid { grid-template-columns: repeat(2, minmax(0,1fr)); } }
</style>