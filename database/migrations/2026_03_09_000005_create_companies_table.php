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
        '2026_03_09_000001_create_country_table' => 'country',
    ];


    public function up(): void
    {
        Schema::createIfNotExists('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('city', 100)->nullable();
            $table->string('street', 100)->nullable();
            $table->integer('number')->nullable();
            $table->string('number_additional', 100)->nullable();
            $table->string('zip_code', 25)->nullable();
            $table->string('country', 2);
            $table->string('email', 100)->nullable();
            
            $table->foreign('country', 'fk_company_country')
                ->references('country_code')
                ->on('country');
            
            $table->index('country', 'idx_company_country_idx');

            $table->charset = 'utf16';
            $table->collation = 'utf16_unicode_ci';

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
