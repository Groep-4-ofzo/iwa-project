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
        '2026_03_09_000002_create_station_table' => 'station',
        '2026_03_09_000001_create_country_table' => 'country',
    ];

    public function up(): void
    {
        Schema::createIfNotExists('geolocation', function (Blueprint $table) {

            $table->id();
            $table->string('station_name', 10);
            $table->string('country_code', 2);
            $table->string('island', 100)->nullable();
            $table->string('county', 100)->nullable();
            $table->string('place', 100)->nullable();
            $table->string('hamlet', 100)->nullable();
            $table->string('town', 100)->nullable();
            $table->string('municipality', 100)->nullable();
            $table->string('state_district', 100)->nullable();
            $table->string('administrative', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('village', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('locality', 100)->nullable();
            $table->string('postcode', 100)->nullable();
            $table->string('country', 100)->nullable();

            $table->index('station_name', 'idx_geolocation_station_name');
            $table->index('country_code', 'idx_geolocation_country_code');

            $table->foreign('station_name', 'fk_geolocation_station_name')
                ->references('name')
                ->on('station');

            $table->foreign('country_code', 'fk_geolocation_country_code')
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
        Schema::dropIfExists('geolocation');
    }
};
