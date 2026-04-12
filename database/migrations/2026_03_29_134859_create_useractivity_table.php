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
        Schema::create('useractivity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userid');
            $table->string('endpoint_used', 256);
            $table->integer('files_downloaded');
            $table->date('activity_date');
            $table->time('activity_time');
            $table->tinyInteger('authorized')->nullable();
            $table->integer('data_transferred')->nullable();

            $table->index('userid', 'idx_UserActivity_identifier');

            $table->foreign('userid', 'fk_UserActivity_identifier')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('useractivity');
    }
};
