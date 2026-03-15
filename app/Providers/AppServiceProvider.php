<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StripeService;          // ✅ AGREGAR esta línea

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ✅ AGREGAR estas 3 líneas dentro de register()
        $this->app->singleton(StripeService::class, function () {
            return new StripeService();
        });
    }

    public function boot(): void
    {
        // No toques nada aquí, déjalo como está
    }
}


// ══════════════════════════════════════════════════════════════
// GUÍA COMPLETA — Setup de Stripe paso a paso
// ══════════════════════════════════════════════════════════════

/*

────────────────────────────────────────────────
PASO 1 — Instalar el SDK de Stripe
────────────────────────────────────────────────
En tu terminal, dentro de la carpeta del proyecto:

    composer require stripe/stripe-php

────────────────────────────────────────────────
PASO 2 — Agregar variables al .env
────────────────────────────────────────────────
Abre el archivo .env (está en la raíz del proyecto) y agrega:

    STRIPE_KEY=pk_test_...
    STRIPE_SECRET=sk_test_...
    STRIPE_WEBHOOK_SECRET=whsec_...
    STRIPE_PRICE_BASICO=price_...
    STRIPE_PRICE_PROFESIONAL=price_...
    STRIPE_PRICE_EMPRESARIAL=price_...

Dónde consigues estas claves:
→ Ve a https://dashboard.stripe.com
→ Developers → API Keys → copia pk_test y sk_test
→ Los price_xxx los creas en el PASO 4

────────────────────────────────────────────────
PASO 3 — Registrar StripeService ← ESTÁS AQUÍ
────────────────────────────────────────────────
Abre: app/Providers/AppServiceProvider.php
Agrega las 2 líneas marcadas con ✅ arriba en este archivo.
Eso es todo para el paso 3.

────────────────────────────────────────────────
PASO 4 — Crear los productos en Stripe Dashboard
────────────────────────────────────────────────
1. Ve a https://dashboard.stripe.com/products
2. Clic en "+ Add product"
3. Crea 3 productos, uno por plan:

   Producto 1:
   - Name: Plan Básico NOM-035
   - Price: $499.00 MXN
   - Billing period: Monthly
   - Clic "Save product"
   - Copia el ID que empieza con price_xxx

   Producto 2:
   - Name: Plan Profesional NOM-035
   - Price: $1,299.00 MXN
   - Billing period: Monthly
   - Copia price_xxx

   Producto 3:
   - Name: Plan Empresarial NOM-035
   - Price: $2,999.00 MXN
   - Billing period: Monthly
   - Copia price_xxx

4. Pega los 3 IDs en tu .env:
   STRIPE_PRICE_BASICO=price_abc123
   STRIPE_PRICE_PROFESIONAL=price_def456
   STRIPE_PRICE_EMPRESARIAL=price_ghi789

────────────────────────────────────────────────
PASO 5 — Migración para campos de Stripe
────────────────────────────────────────────────
En terminal:

    php artisan make:migration add_stripe_fields_to_tables

Abre el archivo que se creó en database/migrations/
y ponle este contenido:

    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->after('active');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('stripe_subscription_id')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('stripe_customer_id');
        });
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('stripe_subscription_id');
        });
    }

Luego corre:
    php artisan migrate

────────────────────────────────────────────────
PASO 6 — Crear el archivo config/stripe.php
────────────────────────────────────────────────
Crea el archivo config/stripe.php con este contenido:

    <?php
    return [
        'key'            => env('STRIPE_KEY'),
        'secret'         => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'prices' => [
            'basico'      => env('STRIPE_PRICE_BASICO'),
            'profesional' => env('STRIPE_PRICE_PROFESIONAL'),
            'empresarial' => env('STRIPE_PRICE_EMPRESARIAL'),
        ],
    ];

Luego limpia el caché de configuración:
    php artisan config:clear

────────────────────────────────────────────────
PASO 7 — Registrar el webhook en Stripe
────────────────────────────────────────────────
1. Ve a https://dashboard.stripe.com/webhooks
2. Clic en "+ Add endpoint"
3. URL del endpoint:
       https://TUDOMINIO.com/api/stripe/webhook
   (si estás en local usa ngrok, te explico abajo)
4. Selecciona estos eventos:
       invoice.payment_succeeded
       invoice.payment_failed
       customer.subscription.updated
       customer.subscription.deleted
       customer.subscription.trial_will_end
5. Clic "Add endpoint"
6. Copia el "Signing secret" (empieza con whsec_)
7. Pégalo en .env:
       STRIPE_WEBHOOK_SECRET=whsec_...

PARA PRUEBAS EN LOCAL (sin dominio):
- Instala ngrok: https://ngrok.com
- Corre: ngrok http 8000
- Te da una URL tipo: https://abc123.ngrok.io
- Úsala como dominio en el paso 3 de arriba

────────────────────────────────────────────────
PASO 8 — Agregar rutas en routes/api.php
────────────────────────────────────────────────
Abre routes/api.php y agrega:

    use App\Http\Controllers\BillingController;
    use App\Http\Controllers\StripeWebhookController;

    // Rutas protegidas de billing
    Route::middleware(['auth:sanctum', 'company.active'])->group(function () {
        Route::post('/billing/checkout',    [BillingController::class, 'checkout']);
        Route::post('/billing/change-plan', [BillingController::class, 'changePlan']);
        Route::post('/billing/cancel',      [BillingController::class, 'cancel']);
    });

    // Webhook — SIN middleware (Stripe llama directo)
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

Y en routes/web.php agrega dentro del grupo auth:

    use App\Http\Controllers\BillingController;

    Route::get('/billing',        [BillingController::class, 'index'])->name('billing');
    Route::get('/billing/success',[BillingController::class, 'success'])->name('billing.success');
    Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');

────────────────────────────────────────────────
RESUMEN DE ARCHIVOS QUE DEBES CREAR/EDITAR
────────────────────────────────────────────────

EDITAR:
  app/Providers/AppServiceProvider.php  ← agregar 3 líneas (PASO 3)
  routes/api.php                         ← agregar rutas (PASO 8)
  routes/web.php                         ← agregar rutas (PASO 8)
  .env                                   ← agregar variables (PASO 2)

CREAR:
  app/Services/StripeService.php         ← del archivo StripeBackend.php
  app/Http/Controllers/BillingController.php
  app/Http/Controllers/StripeWebhookController.php
  app/Jobs/SendPaymentFailedEmail.php
  app/Jobs/SendSubscriptionRenewedEmail.php
  config/stripe.php                      ← (PASO 6)
  resources/js/Pages/Billing.vue         ← ya lo tienes ✅

*/