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
        Schema::create('fault', function (Blueprint $table) {
            $table->id();

            $table->enum('type_fault', ['MISSING', 'EXTREME_VALUE']);

            $table->string('which_field', 50);

            $table->float('corrected_data');

            $table->unsignedBigInteger('measurement');

            $table->index('measurement', 'idx_fault_measurement');

            $table->foreign('measurement', 'fk_fault_measurement')
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
        Schema::dropIfExists('fault');
    }
};
