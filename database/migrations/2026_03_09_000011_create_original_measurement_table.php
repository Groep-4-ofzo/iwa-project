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
        '2026_03_09_000010_create_measurement_table' => 'measurement'
    ];
    
    public function up(): void
    {
        Schema::createIfNotExists('original_measurement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('corrected_measurement');
            $table->string('missing_field', 32)->nullable();
            $table->float('invalid_temperature')->nullable();
            
            $table->index('corrected_measurement', 'idx_measurement_correction');

            $table->foreign('corrected_measurement', 'fk_measurement_correction')
                ->references('id')
                ->on('measurement')
                ->onUpdate('restrict')
                ->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('original_measurement');
    }
};