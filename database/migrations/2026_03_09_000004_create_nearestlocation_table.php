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
        '2026_03_09_000002_create_station_table' => 'station',
        '2026_03_09_000001_create_country_table' => 'country',
    ];
    public function up(): void
    {
        Schema::createIfNotExists('nearestlocation', function (Blueprint $table) {
            $table->id();
            $table->string('station_name', 10);
            $table->string('name', 100)->nullable();
            $table->string('administrative_region1', 100)->nullable();
            $table->string('administrative_region2', 100)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('longitude', 100)->nullable();
            $table->string('latitude', 100)->nullable();

            $table->index('station_name', 'idx_nearestlocation_station_name');
            $table->index('country_code', 'idx_nearestlocation_country_code');


            $table->foreign('station_name', 'fk_nearestlocation_station_name')
                ->references('name')
                ->on('station');

            $table->foreign('country_code', 'fk_nearestlocation_country_code')
                ->references('country_code')
                ->on('country');
                
            $table->charset = 'utf16';
            $table->collation = 'utf16_unicode_ci';


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nearestlocation');
    }
};
