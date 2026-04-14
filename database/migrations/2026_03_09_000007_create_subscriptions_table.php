<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

require_once __DIR__.'/BaseMigration.php';

return new class extends BaseMigration
{
    /**
     * Run the migrations.
     */
    protected array $dependencies = [
        '2026_03_09_000005_create_companies_table' => 'companies',
        '2026_03_09_000000_create_subscription_types_table' => 'subscription_types',
    ];

    public function up(): void
    {
        Schema::createIfNotExists('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company')->nullable();
            $table->unsignedBigInteger('type')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->float('price');
            $table->string('notes', 256)->nullable();
            $table->string('identifier', 45)->unique();
            $table->string('token', 100);

            $table->index('company', 'idx_subscription_company');
            $table->index('type', 'idx_subscription_type');
            $table->index('identifier', 'idx_subscription_identifier');

            $table->foreign('company')
                ->references('id')
                ->on('companies');

            $table->foreign('type')
                ->references('id')
                ->on('subscription_types');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
