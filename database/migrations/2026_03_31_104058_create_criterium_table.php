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
        Schema::create('criterium', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group');
            $table->unsignedBigInteger('operator');
            $table->unsignedBigInteger('int_value')->nullable();
            $table->string('string_value', 45)->nullable();
            $table->float('float_value')->nullable();
            $table->unsignedBigInteger('value_type');
            $table->unsignedBigInteger('value_comparison');

            $table->index('group', 'idx_Criterium_Group');
            $table->index('operator', 'idx_Criterium_Operator');
            $table->index('value_comparison', 'idx_Criterium_Value_Comparison');

            $table->foreign('group', 'fk_Criterium_Group')
                ->references('id')
                ->on('criterium_group');

            $table->foreign('operator', 'fk_Criterium_Operator')
                ->references('id')
                ->on('operator_type');

            $table->foreign('value_type')
                ->references('id')
                ->on('criterium_type');

            $table->foreign('value_comparison', 'fk_Criterium_Value_Comparison')
                ->references('id')
                ->on('comparison_operator_type');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterium');
    }
};
