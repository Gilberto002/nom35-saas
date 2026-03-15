<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {

        /*
        |---------------------------------------
        | PLANES DEL SaaS
        |---------------------------------------
        */

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price',10,2);
            $table->string('interval')->default('month');
            $table->integer('max_employees');
            $table->integer('max_reports_per_month');
            $table->boolean('api_access')->default(false);
            $table->json('features')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        /*
        |---------------------------------------
        | SUSCRIPCIONES
        |---------------------------------------
        */

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained();
            $table->string('status')->default('active');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('external_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        /*
        |---------------------------------------
        | CAMPOS EXTRA EMPRESA
        |---------------------------------------
        */

        Schema::table('companies', function (Blueprint $table) {

            if (!Schema::hasColumn('companies','logo')) {
                $table->string('logo')->nullable()->after('active');
            }

            if (!Schema::hasColumn('companies','industry')) {
                $table->string('industry')->nullable()->after('logo');
            }

            if (!Schema::hasColumn('companies','employee_count')) {
                $table->integer('employee_count')->default(0)->after('industry');
            }

            if (!Schema::hasColumn('companies','last_activity_at')) {
                $table->timestamp('last_activity_at')->nullable()->after('employee_count');
            }

        });

        /*
        |---------------------------------------
        | ADMINISTRADORES DEL SaaS
        |---------------------------------------
        */

        Schema::create('admin_users', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('admin');
            $table->rememberToken();
            $table->timestamps();

        });

    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('admin_users');

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'logo',
                'industry',
                'employee_count',
                'last_activity_at'
            ]);
        });
    }

};