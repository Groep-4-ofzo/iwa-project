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
        Schema::createIfNotExists('station', function (Blueprint $table) {
            $table->string('name', 10)->primary();
            $table->float('longitude');
            $table->float('latitude');
            $table->float('elevation');

            $table->charset = 'utf16';
            $table->collation = 'utf16_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station');
    }
};
