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
        '2026_03_09_000007_create_subscriptions_table' => 'subscriptions'
    ];

    public function up(): void
    {
        Schema::createIfNotExists('endpoint_activity', function (Blueprint $table) {
            $table->id();
            $table->string('identifier', 45);
            $table->string('endpoint_used', 256);
            $table->integer('files_downloaded');
            $table->date('activity_date');
            $table->time('activity_time');
            $table->tinyInteger('authorized')->nullable();
            $table->integer('data_transferred')->nullable();

            $table->index('identifier', 'idx_Activity_identifier');

            $table->foreign('identifier', 'fk_Activity_identifier')
                ->references('identifier')
                ->on('subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endpoint_activity');
    }
};
