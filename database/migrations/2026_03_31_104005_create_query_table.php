<?php

use Illuminate\Database\Migrations\Migration;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
require_once __DIR__ . '/BaseMigration.php'; 
return new class extends BaseMigration
{
    /**
     * Run the migrations.
     */

    protected array $dependencies = [
        '2026_03_31_103932_create_contract_table' => 'contract'
    ];

    public function up(): void
    {
        Schema::createIfNotExists('query', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->string('omschrijving', 256)->nullable();

            $table->index('contract_id', 'idx_Query_Contract');

            $table->foreign('contract_id', 'fk_Query_Contract')
                ->references('id')
                ->on('contract');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('query');
    }
};
