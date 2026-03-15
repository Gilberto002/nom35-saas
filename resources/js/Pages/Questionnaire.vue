<template>
  <MainLayout current-page="/evaluations" page-title="Evaluación NOM-035">
    <div class="nom-root">

      <!-- ══════════════════════════════════════════
           PASO 0 · SETUP
      ══════════════════════════════════════════ -->
      <Transition name="page-fade" mode="out-in">
      <div v-if="step === 0" class="setup-shell" key="setup">

        <!-- Ambient background -->
        <div class="setup-ambient">
          <div class="amb-orb amb-orb--1"></div>
          <div class="amb-orb amb-orb--2"></div>
          <div class="amb-orb amb-orb--3"></div>
        </div>

        <div class="setup-container">

          <!-- Left panel: brand + context -->
          <div class="setup-brand">
            <div class="brand-emblem">
              <svg viewBox="0 0 56 56" fill="none">
                <rect width="56" height="56" rx="16" fill="url(#eg1)"/>
                <path d="M18 28h20M18 20h20M18 36h13" stroke="white" stroke-width="2.2" stroke-linecap="round"/>
                <defs>
                  <linearGradient id="eg1" x1="0" y1="0" x2="56" y2="56">
                    <stop stop-color="#1D4ED8"/>
                    <stop offset="1" stop-color="#0EA5E9"/>
                  </linearGradient>
                </defs>
              </svg>
            </div>
            <div class="brand-wordmark">
              <span class="brand-nom">NOM-035</span>
              <span class="brand-stfps">STPS</span>
            </div>

            <h1 class="setup-headline">Evaluación de<br/><em>Factores de Riesgo<br/>Psicosocial</em></h1>
            <p class="setup-tagline">
              Instrumento clínico validado para la identificación, análisis
              y prevención de riesgos en el entorno laboral, conforme a la
              norma oficial mexicana NOM-035-STPS-2018.
            </p>

            <div class="setup-pillars">
              <div class="pillar">
                <div class="pillar-icon">
                  <svg viewBox="0 0 20 20" fill="none"><path d="M10 2a8 8 0 100 16A8 8 0 0010 2zM10 6v4l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </div>
                <div>
                  <p class="pillar-title">Evaluación continua</p>
                  <p class="pillar-sub">Seguimiento periódico anual</p>
                </div>
              </div>
              <div class="pillar">
                <div class="pillar-icon">
                  <svg viewBox="0 0 20 20" fill="none"><path d="M3 10a7 7 0 1014 0A7 7 0 003 10zm7-3v3l2.5 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </div>
                <div>
                  <p class="pillar-title">Confidencial</p>
                  <p class="pillar-sub">Datos protegidos y seguros</p>
                </div>
              </div>
              <div class="pillar">
                <div class="pillar-icon">
                  <svg viewBox="0 0 20 20" fill="none"><path d="M5 9l4 4 7-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div>
                  <p class="pillar-title">Validado</p>
                  <p class="pillar-sub">Conforme NOM-035-STPS</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Right panel: form -->
          <div class="setup-form-panel">
            <div class="sfp-inner">
              <p class="sfp-eyebrow">Nueva evaluación</p>
              <h2 class="sfp-title">Configurar sesión</h2>

              <!-- Employee select -->
              <div class="field-group" :class="{ 'field-group--error': setupErrors.employee }">
                <label class="field-label">
                  <span class="field-label-text">Colaborador evaluado</span>
                  <span class="field-required">Requerido</span>
                </label>
                <div class="field-select-wrap">
                  <div class="field-icon field-icon--employee">
                    <svg viewBox="0 0 20 20" fill="none">
                      <circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.4"/>
                      <path d="M3.5 17.5c0-3.6 2.9-6.5 6.5-6.5s6.5 2.9 6.5 6.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                  </div>
                  <select v-model="setup.employee_id" class="field-select">
                    <option value="">Seleccionar colaborador…</option>
                    <option v-for="e in employees" :key="e.id" :value="e.id">
                      {{ e.first_name }} {{ e.last_name }} — {{ e.area_name }}
                    </option>
                  </select>
                  <div class="field-chevron">
                    <svg viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                  </div>
                </div>
                <Transition name="err-fade">
                  <p v-if="setupErrors.employee" class="field-error">
                    <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.2"/><path d="M8 5v4M8 11h.01" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                    {{ setupErrors.employee }}
                  </p>
                </Transition>
              </div>

              <!-- Guide selection -->
              <div class="field-group" :class="{ 'field-group--error': setupErrors.questionnaire }">
                <label class="field-label">
                  <span class="field-label-text">Guía de evaluación</span>
                  <span class="field-required">Requerido</span>
                </label>
                <div class="guides-row">
                  <button
                    v-for="g in questionnaires"
                    :key="g.id"
                    type="button"
                    class="guide-tile"
                    :class="{ 'guide-tile--active': setup.questionnaire_id === g.id }"
                    @click="setup.questionnaire_id = g.id">
                    <div class="gt-roman">{{ romanMap[g.id] }}</div>
                    <div class="gt-label">{{ g.name }}</div>
                    <div class="gt-desc">{{ g.description }}</div>
                    <div class="gt-radio">
                      <span class="gt-radio-inner" v-if="setup.questionnaire_id === g.id">
                        <svg viewBox="0 0 10 10" fill="none"><path d="M2 5l2.5 2.5L8 3" stroke="white" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                      </span>
                    </div>
                  </button>
                </div>
                <Transition name="err-fade">
                  <p v-if="setupErrors.questionnaire" class="field-error">
                    <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.2"/><path d="M8 5v4M8 11h.01" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                    {{ setupErrors.questionnaire }}
                  </p>
                </Transition>
              </div>

              <button class="cta-start" @click="startEvaluation" :disabled="isLoading">
                <Transition name="btn-swap" mode="out-in">
                  <span v-if="!isLoading" class="cta-inner" key="label">
                    Iniciar evaluación
                    <svg viewBox="0 0 20 20" fill="none"><path d="M4 10h12M11 5l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  </span>
                  <span v-else class="cta-inner" key="loading">
                    <span class="spin-ring"></span>
                    Preparando…
                  </span>
                </Transition>
              </button>

              <p class="sfp-disclaimer">
                <svg viewBox="0 0 16 16" fill="none"><path d="M8 2a6 6 0 100 12A6 6 0 008 2zm0 4v4m0 1.5h.01" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/></svg>
                La información de esta evaluación es estrictamente confidencial y
                se utiliza únicamente con fines de bienestar organizacional.
              </p>
            </div>
          </div>

        </div>
      </div>
      </Transition>

      <!-- ══════════════════════════════════════════
           PASO 1 · CUESTIONARIO
      ══════════════════════════════════════════ -->
      <Transition name="page-fade" mode="out-in">
      <div v-if="step === 1" class="quiz-shell" key="quiz">

        <!-- Top rail -->
        <div class="quiz-rail">
          <div class="rail-identity">
            <div class="rail-avatar" :style="{ background: avatarGradient(currentEmployee?.first_name) }">
              {{ initials(currentEmployee?.first_name, currentEmployee?.last_name) }}
            </div>
            <div class="rail-info">
              <p class="rail-name">{{ currentEmployee?.first_name }} {{ currentEmployee?.last_name }}</p>
              <p class="rail-meta">{{ currentEmployee?.area_name }} · {{ currentQuestionnaire?.name }}</p>
            </div>
          </div>

          <div class="rail-stats">
            <div class="rstat">
              <span class="rstat-n">{{ currentQuestionIndex + 1 }}<span class="rstat-total">/ {{ questions.length }}</span></span>
              <span class="rstat-l">Pregunta</span>
            </div>
            <div class="rstat-divider"></div>
            <div class="rstat">
              <span class="rstat-n">{{ answeredCount }}</span>
              <span class="rstat-l">Respondidas</span>
            </div>
            <div class="rstat-divider"></div>
            <div class="rstat">
              <span class="rstat-n rstat-timer">{{ elapsedTime }}</span>
              <span class="rstat-l">Tiempo</span>
            </div>
          </div>
        </div>

        <!-- Progress track -->
        <div class="progress-track">
          <div class="progress-segments">
            <div
              v-for="(q, i) in questions"
              :key="q.id"
              class="progress-seg"
              :class="{
                'progress-seg--done': answers[q.id] !== undefined,
                'progress-seg--current': i === currentQuestionIndex && answers[q.id] === undefined
              }"
              @click="goToQuestion(i)"
              :title="`Pregunta ${i+1}`">
            </div>
          </div>
          <span class="progress-pct">{{ progressPct }}%</span>
        </div>

        <!-- Body: question + options -->
        <div class="quiz-body">

          <!-- Sidebar: dot map -->
          <div class="quiz-sidebar">
            <p class="sidebar-label">Navegación</p>
            <div class="dot-map">
              <button
                v-for="(q, i) in questions"
                :key="q.id"
                class="dmap-btn"
                :class="{
                  'dmap-btn--current':  i === currentQuestionIndex,
                  'dmap-btn--done':     answers[q.id] !== undefined,
                  'dmap-btn--pending':  answers[q.id] === undefined && i !== currentQuestionIndex
                }"
                @click="goToQuestion(i)"
                :title="`P${i+1}`">
                <span>{{ i + 1 }}</span>
              </button>
            </div>
            <div class="dmap-legend">
              <span class="dl-item"><span class="dl-dot dl-dot--done"></span>Respondida</span>
              <span class="dl-item"><span class="dl-dot dl-dot--current"></span>Actual</span>
              <span class="dl-item"><span class="dl-dot dl-dot--pending"></span>Pendiente</span>
            </div>
          </div>

          <!-- Main: card + answers -->
          <div class="quiz-main">
            <Transition name="card-slide" mode="out-in">
            <div class="question-card" :key="currentQuestionIndex">

              <div class="qcard-header">
                <span class="qcard-num">
                  <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="7" fill="url(#qn1)"/><text x="8" y="12" text-anchor="middle" fill="white" font-size="8" font-weight="700" font-family="system-ui">{{ currentQuestionIndex + 1 }}</text>
                  <defs><linearGradient id="qn1" x1="0" y1="0" x2="16" y2="16"><stop stop-color="#1D4ED8"/><stop offset="1" stop-color="#38BDF8"/></linearGradient></defs>
                  </svg>
                  Pregunta {{ currentQuestionIndex + 1 }} de {{ questions.length }}
                </span>
                <span class="qcard-cat" v-if="currentQuestion?.category">{{ currentQuestion.category }}</span>
              </div>

              <p class="qcard-text">{{ currentQuestion?.question }}</p>

              <div class="scale-hint">
                <span>Nunca</span>
                <span class="sh-track">
                  <span v-for="n in 5" :key="n" class="sh-pip" :class="`sh-pip--${n}`"></span>
                </span>
                <span>Siempre</span>
              </div>

              <div class="answer-options">
                <button
                  v-for="opt in answerOptions"
                  :key="opt.value"
                  type="button"
                  class="answer-opt"
                  :class="[
                    `answer-opt--${opt.tone}`,
                    { 'answer-opt--selected': answers[currentQuestion?.id] === opt.value }
                  ]"
                  @click="selectAnswer(currentQuestion?.id, opt.value)">
                  <span class="ao-check">
                    <Transition name="check-pop">
                    <svg v-if="answers[currentQuestion?.id] === opt.value" viewBox="0 0 18 18" fill="none">
                      <circle cx="9" cy="9" r="9" fill="currentColor"/>
                      <path d="M5 9l3 3 5-6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    </Transition>
                    <span v-if="answers[currentQuestion?.id] !== opt.value" class="ao-circle"></span>
                  </span>
                  <span class="ao-label">{{ opt.label }}</span>
                  <span class="ao-freq">{{ opt.freq }}</span>
                </button>
              </div>

            </div>
            </Transition>

            <!-- Navigation controls -->
            <div class="quiz-controls">
              <button class="qctl-btn qctl-btn--ghost" @click="prevQuestion" :disabled="currentQuestionIndex === 0">
                <svg viewBox="0 0 18 18" fill="none"><path d="M11 4L6 9l5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Anterior
              </button>

              <div class="qctl-center">
                <span class="qctl-fraction">{{ currentQuestionIndex + 1 }} / {{ questions.length }}</span>
              </div>

              <div class="qctl-right">
                <button
                  v-if="currentQuestionIndex < questions.length - 1"
                  class="qctl-btn qctl-btn--primary"
                  @click="nextQuestion">
                  Siguiente
                  <svg viewBox="0 0 18 18" fill="none"><path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button
                  v-else
                  class="qctl-btn qctl-btn--finish"
                  @click="openFinishModal"
                  :disabled="answeredCount < questions.length">
                  <svg viewBox="0 0 18 18" fill="none"><path d="M3 9l5 5 7-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  Finalizar
                </button>
              </div>
            </div>

            <Transition name="hint-fade">
            <div class="unanswered-bar" v-if="currentQuestionIndex === questions.length - 1 && answeredCount < questions.length">
              <svg viewBox="0 0 16 16" fill="none"><path d="M8 2a6 6 0 100 12A6 6 0 008 2zm0 4v4m0 1.5h.01" stroke="#D97706" stroke-width="1.2" stroke-linecap="round"/></svg>
              Tienes <strong>{{ questions.length - answeredCount }}</strong> pregunta(s) sin responder.
              Usa la navegación lateral para completarlas.
            </div>
            </Transition>
          </div>

        </div>
      </div>
      </Transition>

      <!-- ══════════════════════════════════════════
           PASO 2 · RESULTADO
      ══════════════════════════════════════════ -->
      <Transition name="page-fade" mode="out-in">
      <div v-if="step === 2" class="result-shell" key="result">

        <div class="result-ambient">
          <div class="ra-orb ra-orb--1" :style="{ background: resultGlow }"></div>
          <div class="ra-orb ra-orb--2"></div>
        </div>

        <div class="result-container">

          <!-- Score panel -->
          <div class="result-score-panel">
            <div class="rsp-inner">

              <div class="rsp-badge" :class="`rsp-badge--${riskKey}`">
                <span class="rsp-badge-dot"></span>
                Nivel de Riesgo {{ result.risk_level }}
              </div>

              <!-- Circular score -->
              <div class="score-ring-wrap">
                <svg class="score-ring" viewBox="0 0 200 200">
                  <circle cx="100" cy="100" r="82" fill="none" stroke="#F1F5F9" stroke-width="16"/>
                  <circle
                    cx="100" cy="100" r="82" fill="none"
                    :stroke="resultColor" stroke-width="16"
                    stroke-linecap="round"
                    :stroke-dasharray="`${scoreArc} 515.2`"
                    stroke-dashoffset="0"
                    transform="rotate(-90 100 100)"
                    class="score-arc"/>
                  <!-- Tick marks -->
                  <g v-for="n in 40" :key="n">
                    <line
                      :x1="100 + 90*Math.cos((n/40)*2*Math.PI - Math.PI/2)"
                      :y1="100 + 90*Math.sin((n/40)*2*Math.PI - Math.PI/2)"
                      :x2="100 + 95*Math.cos((n/40)*2*Math.PI - Math.PI/2)"
                      :y2="100 + 95*Math.sin((n/40)*2*Math.PI - Math.PI/2)"
                      stroke="#E2E8F0" stroke-width="1.2" stroke-linecap="round"/>
                  </g>
                </svg>
                <div class="score-center">
                  <span class="score-val">{{ result.score }}</span>
                  <span class="score-unit">puntos</span>
                  <span class="score-max">de {{ maxScore }}</span>
                </div>
              </div>

              <!-- Risk scale bar -->
              <div class="risk-scale">
                <div class="risk-scale-bar">
                  <div class="rsb-bajo"   :class="{ 'rsb--active': result.risk_level === 'Bajo' }">Bajo</div>
                  <div class="rsb-medio"  :class="{ 'rsb--active': result.risk_level === 'Medio' }">Medio</div>
                  <div class="rsb-alto"   :class="{ 'rsb--active': result.risk_level === 'Alto' }">Alto</div>
                  <div class="rsb-muyalto":class="{ 'rsb--active': result.risk_level === 'Muy Alto' }">Muy Alto</div>
                </div>
                <div class="risk-scale-ranges">
                  <span>0</span><span>20</span><span>45</span><span>70</span><span>+</span>
                </div>
              </div>

              <!-- Employee recap -->
              <div class="result-emp-recap">
                <div class="rer-avatar" :style="{ background: avatarGradient(currentEmployee?.first_name) }">
                  {{ initials(currentEmployee?.first_name, currentEmployee?.last_name) }}
                </div>
                <div>
                  <p class="rer-name">{{ currentEmployee?.first_name }} {{ currentEmployee?.last_name }}</p>
                  <p class="rer-area">{{ currentEmployee?.area_name }}</p>
                </div>
                <div class="rer-date">{{ todayDate }}</div>
              </div>

            </div>
          </div>

          <!-- Right: recommendation + actions -->
          <div class="result-detail-panel">

            <div class="rdp-header">
              <p class="rdp-eyebrow">Evaluación completada</p>
              <h2 class="rdp-title">{{ currentQuestionnaire?.name }}</h2>
            </div>

            <!-- Recommendation card -->
            <div class="rec-card" :class="`rec-card--${riskKey}`">
              <div class="rec-card-accent"></div>
              <div class="rec-content">
                <div class="rec-icon-wrap">
                  <svg viewBox="0 0 24 24" fill="none">
                    <path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm0 5v6m0 2.5h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                  </svg>
                </div>
                <div>
                  <p class="rec-title">Recomendación NOM-035</p>
                  <p class="rec-body">{{ recommendation }}</p>
                </div>
              </div>
            </div>

            <!-- Criteria cards -->
            <div class="criteria-grid">
              <div class="crit-card" v-for="c in criteriaList" :key="c.label">
                <div class="crit-icon" :style="{ color: c.color }">
                  <svg viewBox="0 0 20 20" fill="none" v-html="c.icon"></svg>
                </div>
                <div>
                  <p class="crit-title">{{ c.label }}</p>
                  <p class="crit-val" :style="{ color: c.color }">{{ c.value }}</p>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="result-actions">
              <button class="ract-ghost" @click="newEvaluation">
                <svg viewBox="0 0 18 18" fill="none"><path d="M3 9h12M3 9l4-4M3 9l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Nueva evaluación
              </button>
              <Link href="/results" class="ract-primary">
                Ver todos los resultados
                <svg viewBox="0 0 18 18" fill="none"><path d="M4 9h10M10 4l5 5-5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </Link>
            </div>

            <p class="result-footnote">
              Resultado generado conforme a NOM-035-STPS-2018.
              Folio: <strong>{{ evaluationId }}</strong>
            </p>

          </div>
        </div>
      </div>
      </Transition>

    </div>

    <!-- ══════════════════════════════════════════
         MODAL CONFIRMACIÓN
    ══════════════════════════════════════════ -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showFinishModal" class="modal-overlay" @mousedown.self="showFinishModal = false">
          <div class="modal-dialog">
            <div class="modal-stripe"></div>

            <div class="modal-body">
              <div class="modal-icon">
                <svg viewBox="0 0 60 60" fill="none">
                  <circle cx="30" cy="30" r="28" fill="url(#mg1)" opacity="0.12"/>
                  <circle cx="30" cy="30" r="20" fill="url(#mg1)" opacity="0.18"/>
                  <path d="M18 30l10 10 14-18" stroke="url(#mg1)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                  <defs>
                    <linearGradient id="mg1" x1="0" y1="0" x2="60" y2="60">
                      <stop stop-color="#059669"/><stop offset="1" stop-color="#34D399"/>
                    </linearGradient>
                  </defs>
                </svg>
              </div>

              <h3 class="modal-title">¿Finalizar evaluación?</h3>
              <p class="modal-desc">
                Has respondido <strong class="modal-count">{{ answeredCount }}</strong>
                de <strong class="modal-count">{{ questions.length }}</strong> preguntas.
                El sistema calculará el nivel de riesgo psicosocial de forma automática.
              </p>

              <div class="modal-progress">
                <div class="mp-bar">
                  <div class="mp-fill" :style="{ width: progressPct + '%' }"></div>
                </div>
                <span class="mp-label">{{ progressPct }}% completado</span>
              </div>

              <div class="modal-actions">
                <button class="ma-ghost" @click="showFinishModal = false">
                  <svg viewBox="0 0 16 16" fill="none"><path d="M10 4L6 8l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                  Revisar respuestas
                </button>
                <button class="ma-confirm" @click="finishEvaluation" :disabled="isLoading">
                  <Transition name="btn-swap" mode="out-in">
                    <span v-if="!isLoading" key="lbl">
                      <svg viewBox="0 0 16 16" fill="none"><path d="M3 8l4 4 6-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                      Confirmar y finalizar
                    </span>
                    <span v-else key="spin" class="spin-ring spin-ring--sm"></span>
                  </Transition>
                </button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

  </MainLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import MainLayout from '../Layouts/MainLayout.vue'
import axios from 'axios'

// ── Steps ──
const step       = ref(0)
const isLoading  = ref(false)
const showFinishModal = ref(false)

// ── Setup state ──
const setup = reactive({ employee_id: '', questionnaire_id: null })
const setupErrors = reactive({ employee: '', questionnaire: '' })

// ── Data ──
const employees = ref([])
const questionnaires = ref([
  { id: 1, name: 'Guía de referencia I',   description: 'Acontecimientos traumáticos severos' },
  { id: 2, name: 'Guía de referencia II',  description: 'Factores de riesgo psicosocial' },
  { id: 3, name: 'Guía de referencia III', description: 'Entorno organizacional favorable' },
])
const romanMap = { 1: 'I', 2: 'II', 3: 'III' }

const questions    = ref([])
const answers      = reactive({})
const evaluationId = ref(null)
const result       = reactive({ score: 0, risk_level: '' })
const maxScore     = computed(() => questions.value.length * 4)

// ── Navigation ──
const currentQuestionIndex = ref(0)

// ── Timer ──
const elapsed = ref(0)
let timerInterval = null

// ── Computed ──
const currentEmployee      = computed(() => employees.value.find(e => e.id == setup.employee_id))
const currentQuestionnaire = computed(() => questionnaires.value.find(q => q.id === setup.questionnaire_id))
const currentQuestion      = computed(() => questions.value[currentQuestionIndex.value])
const answeredCount        = computed(() => Object.keys(answers).length)
const progressPct          = computed(() =>
  questions.value.length ? Math.round(answeredCount.value / questions.value.length * 100) : 0
)
const elapsedTime = computed(() => {
  const m = Math.floor(elapsed.value / 60)
  const s = elapsed.value % 60
  return `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`
})

const answerOptions = [
  { label: 'Nunca',         value: 0, freq: '0%',   tone: 'never'  },
  { label: 'Casi nunca',    value: 1, freq: '~25%',  tone: 'rarely' },
  { label: 'Algunas veces', value: 2, freq: '~50%',  tone: 'sometimes' },
  { label: 'Casi siempre',  value: 3, freq: '~75%',  tone: 'often' },
  { label: 'Siempre',       value: 4, freq: '100%',  tone: 'always' },
]

const riskKey = computed(() => ({
  'Bajo':     'bajo',
  'Medio':    'medio',
  'Alto':     'alto',
  'Muy Alto': 'muyalto'
}[result.risk_level] || ''))

const resultColor = computed(() => ({
  bajo:'#22C55E', medio:'#F59E0B', alto:'#EF4444', muyalto:'#7C3AED'
}[riskKey.value] || '#94A3B8'))

const resultGlow = computed(() => ({
  bajo:'rgba(34,197,94,0.15)', medio:'rgba(245,158,11,0.15)',
  alto:'rgba(239,68,68,0.15)', muyalto:'rgba(124,58,237,0.15)'
}[riskKey.value] || 'transparent'))

const scoreArc = computed(() => {
  if (!maxScore.value) return 0
  return Math.min((result.score / maxScore.value) * 515.2, 515.2)
})

const recommendation = computed(() => ({
  'Bajo':
    'El colaborador presenta un nivel de riesgo psicosocial bajo. Se recomienda mantener las condiciones actuales y realizar una evaluación de seguimiento conforme al programa anual.',
  'Medio':
    'Se identificaron factores de riesgo moderados. Es necesario revisar las condiciones de trabajo, implementar medidas preventivas y dar seguimiento en un período no mayor a 3 meses.',
  'Alto':
    'Se detectó un nivel de riesgo alto. Se debe implementar un plan de intervención de manera inmediata, incluyendo revisión de cargas de trabajo, condiciones organizacionales y acompañamiento al colaborador.',
  'Muy Alto':
    'Nivel de riesgo muy alto. Se requiere intervención psicológica inmediata, notificación a STPS conforme al artículo 8 de la NOM-035, revisión exhaustiva del entorno laboral y suspensión preventiva de factores agravantes.',
}[result.risk_level] || ''))

const criteriaList = computed(() => [
  {
    label: 'Preguntas respondidas',
    value: `${answeredCount.value} / ${questions.value.length}`,
    color: '#1D4ED8',
    icon: '<circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.4"/><path d="M3.5 17.5c0-3.6 2.9-6.5 6.5-6.5s6.5 2.9 6.5 6.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>',
  },
  {
    label: 'Tiempo empleado',
    value: elapsedTime.value,
    color: '#0EA5E9',
    icon: '<circle cx="10" cy="10" r="7.5" stroke="currentColor" stroke-width="1.4"/><path d="M10 6v4l3 2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>',
  },
  {
    label: 'Puntaje obtenido',
    value: `${result.score} pts`,
    color: resultColor.value,
    icon: '<path d="M3 14l5-5 3 3 6-8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
  },
  {
    label: 'Clasificación',
    value: result.risk_level,
    color: resultColor.value,
    icon: '<circle cx="10" cy="10" r="7.5" stroke="currentColor" stroke-width="1.4"/><path d="M10 6v4m0 2h.01" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>',
  },
])

const todayDate = computed(() => new Date().toLocaleDateString('es-MX', { day:'2-digit', month:'long', year:'numeric' }))

// ── Methods ──
async function fetchEmployees() {
  try {
    const { data } = await axios.get('/api/employees')
    employees.value = (data.data || data).map(e => ({
      ...e, area_name: e.area?.name || e.area_name || '—'
    }))
  } catch { /* silent */ }
}

function validateSetup() {
  setupErrors.employee = ''
  setupErrors.questionnaire = ''
  let ok = true
  if (!setup.employee_id)      { setupErrors.employee      = 'Selecciona un colaborador'; ok = false }
  if (!setup.questionnaire_id) { setupErrors.questionnaire = 'Selecciona una guía';       ok = false }
  return ok
}

async function startEvaluation() {
  if (!validateSetup()) return
  isLoading.value = true
  try {
    const { data } = await axios.get(`/api/evaluation/questions/${setup.questionnaire_id}`)
    questions.value = data.data || data
    const res = await axios.post('/api/evaluation/start', {
      employee_id: setup.employee_id, questionnaire_id: setup.questionnaire_id,
    })
    evaluationId.value = res.data.evaluation_id || res.data.id
    resetQuizState()
    step.value = 1
  } catch {
    loadDemoQuestions()
    step.value = 1
  } finally { isLoading.value = false }
}

function resetQuizState() {
  Object.keys(answers).forEach(k => delete answers[k])
  currentQuestionIndex.value = 0
  elapsed.value = 0
  timerInterval = setInterval(() => elapsed.value++, 1000)
}

function loadDemoQuestions() {
  questions.value = [
    { id:1,  question: 'Mi trabajo me exige realizar mucho esfuerzo físico.' },
    { id:2,  question: 'Me preocupa sufrir un accidente en mi trabajo.' },
    { id:3,  question: 'Considero que las actividades que realizo son peligrosas.' },
    { id:4,  question: 'Por la cantidad de trabajo debo quedarme tiempo adicional.' },
    { id:5,  question: 'Mi trabajo exige que esté muy concentrado.' },
    { id:6,  question: 'Mi trabajo requiere que memorice mucha información.' },
    { id:7,  question: 'En mi trabajo debo tomar decisiones difíciles muy rápido.' },
    { id:8,  question: 'Mi trabajo exige atender varias tareas al mismo tiempo.' },
    { id:9,  question: 'En mi trabajo soy responsable de cosas de mucho valor.' },
    { id:10, question: 'Mi trabajo incluye responsabilidades sobre el bienestar de otras personas.' },
    { id:11, question: 'Para hacer mi trabajo debo demostrar sentimientos distintos a los míos.' },
    { id:12, question: 'Mi trabajo me exige atender a personas necesitadas de ayuda o enfermas.' },
    { id:13, question: 'Puedo decidir cuánto trabajo realizo durante mi jornada laboral.' },
    { id:14, question: 'Puedo decidir la velocidad a la que trabajo.' },
    { id:15, question: 'Puedo cambiar el orden de las actividades que realizo en mi trabajo.' },
  ]
  evaluationId.value = 'DEMO-' + Date.now()
  resetQuizState()
}

function selectAnswer(qId, value) {
  answers[qId] = value
  if (currentQuestionIndex.value < questions.value.length - 1) {
    setTimeout(() => nextQuestion(), 380)
  }
}

function nextQuestion() { if (currentQuestionIndex.value < questions.value.length - 1) currentQuestionIndex.value++ }
function prevQuestion() { if (currentQuestionIndex.value > 0) currentQuestionIndex.value-- }
function goToQuestion(i) { currentQuestionIndex.value = i }

function openFinishModal() {
  if (answeredCount.value < questions.value.length) return
  showFinishModal.value = true
}

async function finishEvaluation() {
  isLoading.value = true
  showFinishModal.value = false
  const payload = Object.entries(answers).map(([q, v]) => ({ question_id: Number(q), value: v }))
  try {
    await axios.post('/api/evaluation/answer', { evaluation_id: evaluationId.value, answers: payload })
    const { data } = await axios.post('/api/evaluation/finish', { evaluation_id: evaluationId.value })
    result.score = data.score
    result.risk_level = data.risk_level
  } catch {
    const total = Object.values(answers).reduce((a, b) => a + b, 0)
    result.score = total
    result.risk_level = total < 20 ? 'Bajo' : total < 45 ? 'Medio' : total < 70 ? 'Alto' : 'Muy Alto'
  } finally {
    clearInterval(timerInterval)
    isLoading.value = false
    step.value = 2
  }
}

function newEvaluation() {
  clearInterval(timerInterval)
  step.value = 0
  setup.employee_id = ''
  setup.questionnaire_id = null
  Object.keys(answers).forEach(k => delete answers[k])
  currentQuestionIndex.value = 0
  result.score = 0
  result.risk_level = ''
  elapsed.value = 0
}

function initials(f, l) { return `${f?.[0]||''}${l?.[0]||''}`.toUpperCase() }
const gradients = [
  'linear-gradient(135deg,#1D4ED8,#38BDF8)',
  'linear-gradient(135deg,#7C3AED,#C084FC)',
  'linear-gradient(135deg,#059669,#34D399)',
  'linear-gradient(135deg,#B45309,#FCD34D)',
]
function avatarGradient(n) { return gradients[(n?.charCodeAt(0)||0) % gradients.length] }

onMounted(() => {
  fetchEmployees()
  const p = new URLSearchParams(window.location.search)
  if (p.get('employee_id')) setup.employee_id = p.get('employee_id')
})
onUnmounted(() => clearInterval(timerInterval))
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;1,700&display=swap');

/* ══ RESET & ROOT ══ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.nom-root {
  font-family: 'Manrope', sans-serif;
  color: #0F172A;
  --blue-deep:  #1E3A5F;
  --blue-mid:   #1D4ED8;
  --blue-sky:   #0EA5E9;
  --slate-5:    #F8FAFC;
  --slate-10:   #F1F5F9;
  --slate-20:   #E2E8F0;
  --slate-40:   #CBD5E1;
  --slate-60:   #94A3B8;
  --slate-80:   #475569;
  --ink:        #0F172A;
  --green:      #059669;
  --amber:      #D97706;
  --red:        #DC2626;
  --purple:     #7C3AED;
}

/* ══════════════════════════════════
   SETUP SCREEN
══════════════════════════════════ */
.setup-shell {
  position: relative;
  min-height: 100vh;
  display: flex; align-items: center; justify-content: center;
  padding: 48px 24px;
  overflow: hidden;
}

/* Ambient background orbs */
.setup-ambient { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
.amb-orb {
  position: absolute; border-radius: 50%;
  filter: blur(80px); opacity: 0.35;
}
.amb-orb--1 {
  width: 600px; height: 600px;
  background: radial-gradient(circle, #BFDBFE 0%, transparent 70%);
  top: -200px; left: -150px;
}
.amb-orb--2 {
  width: 400px; height: 400px;
  background: radial-gradient(circle, #DDD6FE 0%, transparent 70%);
  bottom: -100px; right: -100px;
}
.amb-orb--3 {
  width: 300px; height: 300px;
  background: radial-gradient(circle, #BAE6FD 0%, transparent 70%);
  top: 50%; right: 20%; transform: translateY(-50%);
}

.setup-container {
  position: relative; z-index: 1;
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 0; max-width: 1040px; width: 100%;
  background: white;
  border-radius: 28px;
  border: 1px solid var(--slate-20);
  box-shadow: 0 8px 48px rgba(15,23,42,0.08), 0 2px 8px rgba(15,23,42,0.04);
  overflow: hidden;
}

/* Brand panel */
.setup-brand {
  background: linear-gradient(160deg, var(--blue-deep) 0%, #0F2744 60%, #0C1F3F 100%);
  padding: 48px 44px;
  display: flex; flex-direction: column; gap: 28px;
  position: relative; overflow: hidden;
}
.setup-brand::before {
  content: '';
  position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.brand-emblem { display: flex; align-items: center; gap: 14px; }
.brand-emblem svg { width: 56px; height: 56px; flex-shrink: 0; }
.brand-wordmark { display: flex; flex-direction: column; }
.brand-nom  { font-size: 22px; font-weight: 800; color: white; letter-spacing: -0.5px; line-height: 1; }
.brand-stfps { font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.5); letter-spacing: 2px; text-transform: uppercase; margin-top: 3px; }

.setup-headline {
  font-family: 'Playfair Display', serif;
  font-size: 34px; font-weight: 700; line-height: 1.25;
  color: white;
}
.setup-headline em { font-style: italic; color: #93C5FD; }

.setup-tagline {
  font-size: 13.5px; color: rgba(255,255,255,0.55); line-height: 1.75;
  border-top: 1px solid rgba(255,255,255,0.1); padding-top: 24px;
}

.setup-pillars { display: flex; flex-direction: column; gap: 14px; margin-top: auto; }
.pillar { display: flex; align-items: center; gap: 12px; }
.pillar-icon {
  width: 36px; height: 36px; border-radius: 10px;
  background: rgba(255,255,255,0.1);
  display: flex; align-items: center; justify-content: center;
  color: #93C5FD; flex-shrink: 0;
}
.pillar-icon svg { width: 18px; height: 18px; }
.pillar-title { font-size: 13px; font-weight: 700; color: white; }
.pillar-sub   { font-size: 11px; color: rgba(255,255,255,0.45); margin-top: 1px; }

/* Form panel */
.setup-form-panel {
  padding: 48px 44px;
  display: flex; flex-direction: column; justify-content: center;
}
.sfp-inner { display: flex; flex-direction: column; gap: 28px; }
.sfp-eyebrow {
  font-size: 11px; font-weight: 700; color: var(--blue-mid);
  text-transform: uppercase; letter-spacing: 1.5px;
}
.sfp-title {
  font-family: 'Playfair Display', serif;
  font-size: 28px; font-weight: 700; color: var(--blue-deep);
  margin-top: -12px;
}

/* Field */
.field-group { display: flex; flex-direction: column; gap: 8px; }
.field-label {
  display: flex; align-items: center; justify-content: space-between;
  font-size: 11.5px; font-weight: 700; color: var(--slate-80);
  text-transform: uppercase; letter-spacing: 0.6px;
}
.field-required {
  font-size: 10px; font-weight: 600; color: #EF4444;
  background: #FEF2F2; padding: 2px 8px; border-radius: 4px;
  letter-spacing: 0.3px;
}

.field-select-wrap { position: relative; }
.field-icon {
  position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
  width: 17px; height: 17px; color: var(--slate-60); pointer-events: none; z-index: 1;
}
.field-icon svg { width: 17px; height: 17px; }
.field-chevron {
  position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
  width: 16px; height: 16px; color: var(--slate-60); pointer-events: none;
}
.field-chevron svg { width: 16px; height: 16px; }

.field-select {
  width: 100%; padding: 13px 44px 13px 42px;
  border: 1.5px solid var(--slate-20); border-radius: 12px;
  font-family: 'Manrope', sans-serif; font-size: 14px; font-weight: 500; color: var(--ink);
  background: var(--slate-5); appearance: none; outline: none; cursor: pointer;
  transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}
.field-select:focus {
  border-color: var(--blue-mid);
  box-shadow: 0 0 0 3px rgba(29,78,216,0.1);
  background: white;
}
.field-group--error .field-select { border-color: #F87171; }

.field-error {
  display: flex; align-items: center; gap: 6px;
  font-size: 12px; color: #EF4444; font-weight: 500;
}
.field-error svg { width: 13px; height: 13px; flex-shrink: 0; }

/* Guide tiles */
.guides-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.guide-tile {
  position: relative; border: 2px solid var(--slate-20);
  border-radius: 14px; padding: 16px 14px 14px;
  cursor: pointer; transition: all 0.2s; text-align: left;
  background: var(--slate-5);
}
.guide-tile:hover { border-color: #BFDBFE; background: #EFF6FF; }
.guide-tile--active { border-color: var(--blue-mid); background: #EFF6FF; }
.guide-tile--active::before {
  content: '';
  position: absolute; inset: 0; border-radius: 12px;
  box-shadow: 0 0 0 3px rgba(29,78,216,0.12);
  pointer-events: none;
}
.gt-roman {
  font-size: 22px; font-weight: 800; color: var(--blue-mid);
  font-family: 'Playfair Display', serif; line-height: 1;
  margin-bottom: 8px;
}
.gt-label { font-size: 11.5px; font-weight: 700; color: var(--blue-deep); margin-bottom: 4px; }
.gt-desc  { font-size: 10.5px; color: var(--slate-60); line-height: 1.4; }
.gt-radio {
  position: absolute; top: 10px; right: 10px;
  width: 20px; height: 20px; border-radius: 50%;
  border: 2px solid var(--slate-40);
  background: white;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.2s;
}
.guide-tile--active .gt-radio { border-color: var(--blue-mid); background: var(--blue-mid); }
.gt-radio-inner svg { width: 10px; height: 10px; }

/* CTA button */
.cta-start {
  width: 100%; padding: 16px;
  background: linear-gradient(135deg, var(--blue-mid), var(--blue-sky));
  color: white; border: none; border-radius: 14px;
  font-family: 'Manrope', sans-serif; font-size: 15px; font-weight: 700;
  cursor: pointer; transition: all 0.25s;
  position: relative; overflow: hidden;
}
.cta-start::after {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 60%);
  border-radius: 14px;
}
.cta-start:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(29,78,216,0.35); }
.cta-start:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
.cta-inner { display: inline-flex; align-items: center; justify-content: center; gap: 10px; }
.cta-inner svg { width: 18px; height: 18px; }

.sfp-disclaimer {
  display: flex; align-items: flex-start; gap: 8px;
  font-size: 11.5px; color: var(--slate-60); line-height: 1.65;
  background: var(--slate-5); border: 1px solid var(--slate-20);
  border-radius: 10px; padding: 12px 14px;
}
.sfp-disclaimer svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; }

/* ══════════════════════════════════
   QUIZ SCREEN
══════════════════════════════════ */
.quiz-shell {
  display: flex; flex-direction: column; gap: 16px;
  max-width: 1100px; margin: 0 auto; width: 100%;
  padding: 4px 0;
}

/* Rail */
.quiz-rail {
  background: white; border-radius: 16px; padding: 16px 24px;
  border: 1px solid var(--slate-20);
  display: flex; align-items: center; justify-content: space-between; gap: 20px;
  box-shadow: 0 1px 4px rgba(15,23,42,0.04);
}
.rail-identity { display: flex; align-items: center; gap: 12px; }
.rail-avatar {
  width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 800; color: white; letter-spacing: 0.5px;
}
.rail-name { font-size: 15px; font-weight: 700; color: var(--blue-deep); }
.rail-meta { font-size: 12px; color: var(--slate-60); margin-top: 2px; }

.rail-stats { display: flex; align-items: center; gap: 0; }
.rstat { text-align: center; padding: 0 20px; }
.rstat-n  { display: block; font-size: 22px; font-weight: 800; color: var(--blue-deep); line-height: 1; font-variant-numeric: tabular-nums; }
.rstat-total { font-size: 13px; font-weight: 500; color: var(--slate-60); }
.rstat-l  { display: block; font-size: 10px; font-weight: 700; color: var(--slate-60); text-transform: uppercase; letter-spacing: 0.8px; margin-top: 3px; }
.rstat-timer { font-family: 'Courier New', monospace; font-size: 20px; }
.rstat-divider { width: 1px; height: 36px; background: var(--slate-20); }

/* Progress track */
.progress-track {
  display: flex; align-items: center; gap: 12px;
  padding: 0 2px;
}
.progress-segments {
  flex: 1; display: flex; gap: 3px; height: 6px;
}
.progress-seg {
  flex: 1; height: 6px; border-radius: 100px;
  background: var(--slate-20); cursor: pointer;
  transition: all 0.25s;
}
.progress-seg--done    { background: #22C55E; }
.progress-seg--current { background: var(--blue-sky); animation: seg-pulse 2s ease-in-out infinite; }
@keyframes seg-pulse { 0%,100%{opacity:1} 50%{opacity:0.6} }
.progress-pct { font-size: 12px; font-weight: 700; color: var(--blue-mid); white-space: nowrap; min-width: 38px; text-align: right; }

/* Quiz body */
.quiz-body { display: grid; grid-template-columns: 200px 1fr; gap: 16px; align-items: start; }

/* Sidebar */
.quiz-sidebar {
  background: white; border-radius: 16px; padding: 20px;
  border: 1px solid var(--slate-20); position: sticky; top: 20px;
}
.sidebar-label {
  font-size: 10px; font-weight: 700; color: var(--slate-60);
  text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px;
}
.dot-map { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 16px; }
.dmap-btn {
  width: 28px; height: 28px; border-radius: 8px; border: 1.5px solid var(--slate-20);
  font-size: 10px; font-weight: 700; cursor: pointer; transition: all 0.18s;
  display: flex; align-items: center; justify-content: center;
}
.dmap-btn--pending { background: var(--slate-5); color: var(--slate-60); }
.dmap-btn--done    { background: #DCFCE7; border-color: #86EFAC; color: #15803D; }
.dmap-btn--current { background: var(--blue-mid); border-color: var(--blue-mid); color: white; transform: scale(1.15); }
.dmap-legend { display: flex; flex-direction: column; gap: 6px; border-top: 1px solid var(--slate-20); padding-top: 12px; }
.dl-item { display: flex; align-items: center; gap: 7px; font-size: 10.5px; color: var(--slate-80); }
.dl-dot  { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.dl-dot--done    { background: #22C55E; }
.dl-dot--current { background: var(--blue-mid); }
.dl-dot--pending { background: var(--slate-40); }

/* Question card */
.quiz-main { display: flex; flex-direction: column; gap: 14px; }

.question-card {
  background: white; border-radius: 20px; padding: 36px;
  border: 1px solid var(--slate-20);
  box-shadow: 0 2px 12px rgba(15,23,42,0.06);
}

.qcard-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.qcard-num {
  display: flex; align-items: center; gap: 8px;
  font-size: 12px; font-weight: 700; color: var(--blue-mid);
  text-transform: uppercase; letter-spacing: 0.8px;
}
.qcard-num svg { width: 20px; height: 20px; }
.qcard-cat {
  font-size: 11px; font-weight: 700; color: var(--slate-60);
  background: var(--slate-10); padding: 4px 10px; border-radius: 6px;
  text-transform: uppercase; letter-spacing: 0.5px;
}

.qcard-text {
  font-size: 19px; font-weight: 600; color: var(--blue-deep); line-height: 1.6;
  margin-bottom: 24px;
  font-family: 'Playfair Display', serif;
}

/* Scale hint */
.scale-hint {
  display: flex; align-items: center; gap: 10px;
  font-size: 11px; color: var(--slate-60); margin-bottom: 20px;
}
.sh-track { flex: 1; display: flex; gap: 3px; }
.sh-pip {
  flex: 1; height: 3px; border-radius: 100px;
}
.sh-pip--1 { background: #22C55E; }
.sh-pip--2 { background: #86EFAC; }
.sh-pip--3 { background: #FCD34D; }
.sh-pip--4 { background: #FCA5A5; }
.sh-pip--5 { background: #EF4444; }

/* Answer options */
.answer-options { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; }
.answer-opt {
  display: flex; flex-direction: column; align-items: center; gap: 8px;
  padding: 14px 8px; border-radius: 14px;
  border: 2px solid var(--slate-20);
  background: var(--slate-5); cursor: pointer;
  transition: all 0.2s;
}
.answer-opt:hover { background: #EFF6FF; border-color: #BFDBFE; }
.ao-check { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; }
.ao-check svg { width: 24px; height: 24px; }
.ao-circle {
  width: 22px; height: 22px; border-radius: 50%;
  border: 2px solid var(--slate-40); display: block; transition: border-color 0.2s;
}
.answer-opt:hover .ao-circle { border-color: var(--blue-sky); }
.ao-label { font-size: 12px; font-weight: 700; color: var(--slate-80); text-align: center; line-height: 1.3; }
.ao-freq  { font-size: 10px; color: var(--slate-40); }

/* Tone-specific hover */
.answer-opt--never:hover     { border-color: #86EFAC; background: #F0FDF4; }
.answer-opt--rarely:hover    { border-color: #A7F3D0; background: #F0FDF4; }
.answer-opt--sometimes:hover { border-color: #FDE68A; background: #FFFBEB; }
.answer-opt--often:hover     { border-color: #FCA5A5; background: #FEF2F2; }
.answer-opt--always:hover    { border-color: #FCA5A5; background: #FEF2F2; }

.answer-opt--selected {
  border-color: var(--blue-mid) !important;
  background: #EFF6FF !important;
}
.answer-opt--selected .ao-label { color: var(--blue-mid); }
.answer-opt--selected .ao-check { color: var(--blue-mid); }

/* Controls */
.quiz-controls {
  background: white; border-radius: 14px; padding: 14px 20px;
  border: 1px solid var(--slate-20);
  display: flex; align-items: center; gap: 12px;
}
.qctl-btn {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 10px 20px; border-radius: 10px;
  font-family: 'Manrope', sans-serif; font-size: 14px; font-weight: 700;
  cursor: pointer; transition: all 0.18s; border: 1.5px solid;
}
.qctl-btn svg { width: 16px; height: 16px; }
.qctl-btn--ghost {
  background: white; color: var(--slate-80); border-color: var(--slate-20);
}
.qctl-btn--ghost:hover:not(:disabled) { border-color: var(--slate-40); color: var(--ink); }
.qctl-btn--ghost:disabled { opacity: 0.35; cursor: not-allowed; }
.qctl-btn--primary {
  background: #EFF6FF; color: var(--blue-mid); border-color: #BFDBFE;
}
.qctl-btn--primary:hover { background: #DBEAFE; border-color: #93C5FD; }
.qctl-btn--finish {
  background: linear-gradient(135deg, var(--green), #34D399);
  color: white; border-color: transparent;
}
.qctl-btn--finish:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(5,150,105,0.3); }
.qctl-btn--finish:disabled { opacity: 0.5; cursor: not-allowed; }
.qctl-center { flex: 1; display: flex; justify-content: center; }
.qctl-fraction { font-size: 13px; color: var(--slate-60); font-weight: 600; }
.qctl-right { margin-left: auto; }

.unanswered-bar {
  display: flex; align-items: center; gap: 8px;
  padding: 12px 16px; border-radius: 10px;
  background: #FFFBEB; border: 1px solid #FDE68A;
  font-size: 13px; color: #92400E;
}
.unanswered-bar svg { width: 15px; height: 15px; flex-shrink: 0; }

/* ══════════════════════════════════
   RESULT SCREEN
══════════════════════════════════ */
.result-shell {
  position: relative; min-height: 100vh;
  display: flex; align-items: center; justify-content: center;
  padding: 40px 24px; overflow: hidden;
}
.result-ambient { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
.ra-orb {
  position: absolute; border-radius: 50%;
  filter: blur(100px);
}
.ra-orb--1 { width: 600px; height: 600px; top: -100px; right: -100px; transition: background 1s; }
.ra-orb--2 {
  width: 400px; height: 400px; bottom: -100px; left: -50px;
  background: radial-gradient(circle, rgba(191,219,254,0.3) 0%, transparent 70%);
}

.result-container {
  position: relative; z-index: 1;
  display: grid; grid-template-columns: 380px 1fr;
  max-width: 1000px; width: 100%;
  background: white; border-radius: 28px;
  border: 1px solid var(--slate-20);
  box-shadow: 0 8px 48px rgba(15,23,42,0.1);
  overflow: hidden;
}

/* Score panel */
.result-score-panel {
  background: var(--slate-5); border-right: 1px solid var(--slate-20);
  padding: 44px 36px;
  display: flex; flex-direction: column;
}
.rsp-inner { display: flex; flex-direction: column; align-items: center; gap: 28px; text-align: center; }

.rsp-badge {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: 13px; font-weight: 800; padding: 7px 18px;
  border-radius: 100px; letter-spacing: 0.3px;
}
.rsp-badge-dot { width: 8px; height: 8px; border-radius: 50%; background: currentColor; }
.rsp-badge--bajo    { background: #DCFCE7; color: #15803D; }
.rsp-badge--medio   { background: #FEF9C3; color: #A16207; }
.rsp-badge--alto    { background: #FEE2E2; color: #B91C1C; }
.rsp-badge--muyalto { background: #EDE9FE; color: #6D28D9; }

/* Score ring */
.score-ring-wrap { position: relative; width: 200px; height: 200px; }
.score-ring { width: 200px; height: 200px; }
.score-arc { transition: stroke-dasharray 1.8s cubic-bezier(0.34,1.56,0.64,1); }
.score-center {
  position: absolute; inset: 0;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
}
.score-val { font-size: 52px; font-weight: 800; color: var(--blue-deep); line-height: 1; font-variant-numeric: tabular-nums; }
.score-unit { font-size: 13px; color: var(--slate-60); margin-top: 4px; font-weight: 600; }
.score-max  { font-size: 11px; color: var(--slate-40); margin-top: 2px; }

/* Risk scale bar */
.risk-scale { width: 100%; }
.risk-scale-bar {
  display: grid; grid-template-columns: repeat(4, 1fr);
  border-radius: 10px; overflow: hidden; height: 36px;
}
.rsb-bajo    { background: #86EFAC; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#14532D; }
.rsb-medio   { background: #FCD34D; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#78350F; }
.rsb-alto    { background: #FCA5A5; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#7F1D1D; }
.rsb-muyalto { background: #C4B5FD; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#4C1D95; }
.rsb--active { outline: 2px solid var(--ink); outline-offset: 2px; z-index: 1; position: relative; }
.risk-scale-ranges {
  display: flex; justify-content: space-between;
  padding: 5px 2px 0;
  font-size: 10px; color: var(--slate-60); font-weight: 600;
}

/* Recap */
.result-emp-recap {
  display: flex; align-items: center; gap: 12px; width: 100%;
  padding: 16px; background: white; border-radius: 14px;
  border: 1px solid var(--slate-20);
}
.rer-avatar {
  width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 800; color: white;
}
.rer-name { font-size: 14px; font-weight: 700; color: var(--blue-deep); }
.rer-area { font-size: 11px; color: var(--slate-60); margin-top: 2px; }
.rer-date { margin-left: auto; font-size: 11px; color: var(--slate-60); white-space: nowrap; }

/* Detail panel */
.result-detail-panel {
  padding: 44px 40px;
  display: flex; flex-direction: column; gap: 28px;
}
.rdp-eyebrow {
  font-size: 11px; font-weight: 700; color: var(--blue-mid);
  text-transform: uppercase; letter-spacing: 1.5px;
}
.rdp-title {
  font-family: 'Playfair Display', serif;
  font-size: 26px; font-weight: 700; color: var(--blue-deep);
  margin-top: -12px;
}

/* Recommendation */
.rec-card {
  border-radius: 16px; border: 1px solid;
  overflow: hidden; position: relative;
}
.rec-card--bajo    { background: #F0FDF4; border-color: #BBF7D0; }
.rec-card--medio   { background: #FFFBEB; border-color: #FDE68A; }
.rec-card--alto    { background: #FEF2F2; border-color: #FECACA; }
.rec-card--muyalto { background: #F5F3FF; border-color: #DDD6FE; }
.rec-card-accent {
  position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
}
.rec-card--bajo    .rec-card-accent { background: #22C55E; }
.rec-card--medio   .rec-card-accent { background: #F59E0B; }
.rec-card--alto    .rec-card-accent { background: #EF4444; }
.rec-card--muyalto .rec-card-accent { background: #7C3AED; }

.rec-content { display: flex; gap: 14px; padding: 18px 18px 18px 22px; }
.rec-icon-wrap { width: 22px; height: 22px; flex-shrink: 0; margin-top: 2px; }
.rec-icon-wrap svg { width: 22px; height: 22px; }
.rec-card--bajo    .rec-icon-wrap { color: #15803D; }
.rec-card--medio   .rec-icon-wrap { color: #A16207; }
.rec-card--alto    .rec-icon-wrap { color: #B91C1C; }
.rec-card--muyalto .rec-icon-wrap { color: #6D28D9; }
.rec-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
.rec-body  { font-size: 13.5px; color: var(--slate-80); line-height: 1.7; }

/* Criteria grid */
.criteria-grid {
  display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;
}
.crit-card {
  display: flex; align-items: center; gap: 12px;
  padding: 14px 16px; border-radius: 12px;
  background: var(--slate-5); border: 1px solid var(--slate-20);
}
.crit-icon { width: 20px; height: 20px; flex-shrink: 0; }
.crit-icon svg { width: 20px; height: 20px; }
.crit-title { font-size: 11px; color: var(--slate-60); font-weight: 600; margin-bottom: 2px; }
.crit-val   { font-size: 16px; font-weight: 800; }

/* Actions */
.result-actions { display: flex; gap: 12px; }
.ract-ghost {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 22px; border-radius: 12px;
  background: white; color: var(--slate-80);
  border: 1.5px solid var(--slate-20);
  font-family: 'Manrope', sans-serif; font-size: 14px; font-weight: 700;
  cursor: pointer; transition: all 0.2s;
}
.ract-ghost:hover { border-color: var(--slate-40); color: var(--ink); }
.ract-ghost svg { width: 16px; height: 16px; }
.ract-primary {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 22px; border-radius: 12px;
  background: linear-gradient(135deg, var(--blue-mid), var(--blue-sky));
  color: white; text-decoration: none;
  font-size: 14px; font-weight: 700; transition: all 0.2s;
}
.ract-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(29,78,216,0.3); }
.ract-primary svg { width: 16px; height: 16px; }

.result-footnote {
  font-size: 11px; color: var(--slate-40); line-height: 1.6;
  border-top: 1px solid var(--slate-20); padding-top: 20px; margin-top: auto;
}

/* ══════════════════════════════════
   MODAL
══════════════════════════════════ */
.modal-overlay {
  position: fixed; inset: 0; z-index: 9999;
  background: rgba(15,23,42,0.6); backdrop-filter: blur(6px);
  display: flex; align-items: center; justify-content: center; padding: 24px;
}
.modal-dialog {
  background: white; border-radius: 24px;
  width: 100%; max-width: 440px; overflow: hidden;
  box-shadow: 0 32px 80px rgba(0,0,0,0.25), 0 8px 24px rgba(0,0,0,0.1);
}
.modal-stripe { height: 5px; background: linear-gradient(90deg, var(--green), #34D399); }
.modal-body { padding: 36px; text-align: center; }

.modal-icon { width: 72px; height: 72px; margin: 0 auto 20px; }
.modal-icon svg { width: 72px; height: 72px; }

.modal-title {
  font-family: 'Playfair Display', serif;
  font-size: 22px; font-weight: 700; color: var(--blue-deep); margin-bottom: 10px;
}
.modal-desc { font-size: 14px; color: var(--slate-80); line-height: 1.7; margin-bottom: 20px; }
.modal-count { color: var(--blue-mid); }

.modal-progress { margin-bottom: 28px; }
.mp-bar {
  height: 8px; background: var(--slate-20); border-radius: 100px; overflow: hidden; margin-bottom: 6px;
}
.mp-fill {
  height: 100%; background: linear-gradient(90deg, var(--blue-mid), var(--blue-sky));
  border-radius: 100px; transition: width 0.6s ease;
}
.mp-label { font-size: 12px; font-weight: 700; color: var(--blue-mid); }

.modal-actions { display: flex; gap: 10px; justify-content: center; }
.ma-ghost {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 12px 20px; border-radius: 12px;
  background: white; color: var(--slate-80);
  border: 1.5px solid var(--slate-20);
  font-family: 'Manrope', sans-serif; font-size: 14px; font-weight: 700;
  cursor: pointer; transition: all 0.2s;
}
.ma-ghost:hover { border-color: var(--slate-40); color: var(--ink); }
.ma-ghost svg { width: 14px; height: 14px; }
.ma-confirm {
  display: inline-flex; align-items: center; justify-content: center; gap: 8px;
  padding: 12px 24px; border-radius: 12px; min-width: 160px;
  background: linear-gradient(135deg, #059669, #34D399);
  color: white; border: none;
  font-family: 'Manrope', sans-serif; font-size: 14px; font-weight: 700;
  cursor: pointer; transition: all 0.2s;
}
.ma-confirm:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(5,150,105,0.3); }
.ma-confirm:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
.ma-confirm svg { width: 14px; height: 14px; }
.ma-confirm span { display: inline-flex; align-items: center; gap: 8px; }

/* ══ SPINNERS ══ */
.spin-ring {
  width: 20px; height: 20px; border-radius: 50%;
  border: 2.5px solid rgba(255,255,255,0.3);
  border-top-color: white;
  animation: spin 0.7s linear infinite; display: inline-block;
}
.spin-ring--sm { width: 16px; height: 16px; border-width: 2px; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ══ TRANSITIONS ══ */
.page-fade-enter-active, .page-fade-leave-active { transition: opacity 0.3s ease, transform 0.3s ease; }
.page-fade-enter-from { opacity: 0; transform: translateY(12px); }
.page-fade-leave-to   { opacity: 0; transform: translateY(-8px); }

.card-slide-enter-active, .card-slide-leave-active { transition: opacity 0.22s ease, transform 0.22s ease; }
.card-slide-enter-from { opacity: 0; transform: translateX(20px); }
.card-slide-leave-to   { opacity: 0; transform: translateX(-16px); }

.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.25s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }
.modal-fade-enter-active .modal-dialog, .modal-fade-leave-active .modal-dialog { transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1); }
.modal-fade-enter-from .modal-dialog { transform: scale(0.92) translateY(16px); }
.modal-fade-leave-to .modal-dialog   { transform: scale(0.95) translateY(8px); }

.check-pop-enter-active { transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), opacity 0.2s; }
.check-pop-enter-from   { transform: scale(0); opacity: 0; }

.err-fade-enter-active, .err-fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.err-fade-enter-from, .err-fade-leave-to { opacity: 0; transform: translateY(-4px); }

.btn-swap-enter-active, .btn-swap-leave-active { transition: opacity 0.15s; }
.btn-swap-enter-from, .btn-swap-leave-to { opacity: 0; }

.hint-fade-enter-active, .hint-fade-leave-active { transition: opacity 0.3s, transform 0.3s; }
.hint-fade-enter-from, .hint-fade-leave-to { opacity: 0; transform: translateY(4px); }

/* ══ RESPONSIVE ══ */
@media (max-width: 900px) {
  .setup-container { grid-template-columns: 1fr; }
  .setup-brand { padding: 36px 32px; }
  .setup-headline { font-size: 26px; }
  .quiz-body { grid-template-columns: 1fr; }
  .quiz-sidebar { position: static; }
  .dot-map { display: flex; flex-direction: row; }
  .result-container { grid-template-columns: 1fr; }
  .result-score-panel { border-right: none; border-bottom: 1px solid var(--slate-20); }
  .answer-options { grid-template-columns: repeat(5, 1fr); }
}

@media (max-width: 640px) {
  .quiz-rail { flex-direction: column; align-items: flex-start; }
  .rail-stats { gap: 0; }
  .rstat { padding: 0 12px; }
  .answer-options { grid-template-columns: 1fr; }
  .guides-row { grid-template-columns: 1fr; }
  .result-actions { flex-direction: column; }
  .criteria-grid { grid-template-columns: 1fr; }
  .setup-form-panel { padding: 32px 24px; }
  .result-detail-panel { padding: 32px 24px; }
}
</style>