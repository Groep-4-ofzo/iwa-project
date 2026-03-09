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
        Schema::create('endpoint_activity', function (Blueprint $table) {
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
