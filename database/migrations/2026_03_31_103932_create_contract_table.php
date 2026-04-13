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
        '2026_03_09_000005_create_companies_table' => 'companies'
    ];

    public function up(): void
    {
        Schema::createIfNotExists('contract', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('omschrijving', 256)->nullable();
            $table->date('start_datum');
            $table->date('end_datum')->nullable();
            $table->string('url', 100);

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract');
    }
};
