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
        Schema::create('criterium_group', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('query');
            $table->unsignedBigInteger('type');
            $table->unsignedBigInteger('group_level');
            $table->unsignedBigInteger('operator');

            $table->index('query', 'idx_Criterium_Group_Query');
            $table->index('type', 'idx_Criterium_Group_Type');

            $table->foreign('query', 'fk_Criterium_Group_Query')
                ->references('id')
                ->on('query');
            $table->foreign('type', 'fk_Criterium_Group_Type')
                ->references('id')
                ->on('criterium_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterium_group');
    }
};
