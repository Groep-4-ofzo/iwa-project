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
        '2026_03_06_000001_create_users_table' => 'users',
    ];

    public function up(): void
    {
        Schema::createIfNotExists('useractivity', function (Blueprint $table) {
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
