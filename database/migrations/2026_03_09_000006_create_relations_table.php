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
    ];

    public function up(): void
    {
        Schema::createIfNotExists('relations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('first_name', 45)->nullable();
            $table->string('initials', 12)->nullable();
            $table->string('prefix', 10)->nullable();
            $table->unsignedBigInteger('company');
            $table->string('function', 45)->nullable();
            $table->string('title', 45)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 25)->nullable();

            $table->index('company', 'idx_relation_company_idx');
            $table->foreign('company', 'fk_relation_company')
                ->references('id')
                ->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relations');
    }
};
