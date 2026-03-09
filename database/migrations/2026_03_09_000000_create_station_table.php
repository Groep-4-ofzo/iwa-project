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
        Schema::create('station', function (Blueprint $table) {
            $table->string('name', 10)->primary();
            $table->decimal('longitude', 8);
            $table->decimal('latitude', 8);
            $table->decimal('elevation', 8);

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
