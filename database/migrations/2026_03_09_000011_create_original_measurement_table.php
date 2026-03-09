<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('original_measurement', function (Blueprint $table) {
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