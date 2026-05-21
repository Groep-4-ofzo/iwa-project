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
        Schema::createIfNotExists('app_users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('email', 100);
            $table->string('identifier', 10);
            $table->string('password', 256);
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->unsignedBigInteger('contract_id')->nullable();

            $table->foreign('contract_id', 'fk_contract_of_user')
                ->references('id')
                ->on('contract');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_users');
    }
};
