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
        '2026_03_06_000000_create_userroles_table' => 'userroles'
    ];
    public function up(): void
    {
        Schema::createIfNotExists('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('first_name', 45)->nullable();
            $table->string('initials', 12)->nullable();
            $table->string('prefix', 10)->nullable();
            $table->string('email', 100);
            $table->string('employee_code', 10);
            $table->unsignedBigInteger('user_role');
            $table->string('password', 256);

            $table->index('user_role', 'idx_role_for_user_idx');

            $table->foreign('user_role', 'fk_role_for_user')
                ->references('id')
                ->on('userroles');


        });

        Schema::createIfNotExists('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 100)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::createIfNotExists('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
