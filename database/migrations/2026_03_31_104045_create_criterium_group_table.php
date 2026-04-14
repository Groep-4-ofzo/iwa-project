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
        '2026_03_31_104005_create_query_table' => 'query',
        '2026_03_31_104113_create_criterium_type_table' => 'criterium_type',
        '2026_03_31_104125_create_operator_type_table' => 'comparison_operator_type',
    ];

    public function up(): void
    {
        Schema::createIfNotExists('criterium_group', function (Blueprint $table) {
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

            $table->foreign('operator')
                ->references('id')
                ->on('operator_type');
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
