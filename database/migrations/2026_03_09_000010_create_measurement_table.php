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
        '2026_03_09_000002_create_station_table' => 'stations',
    ];

    public function up(): void
    {
        Schema::createIfNotExists('measurement', function (Blueprint $table) {
            $table->id();
            $table->string('station', 10)->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->float('temperature')->nullable();
            $table->float('dewpoint_temperature')->nullable();
            $table->float('air_pressure_station')->nullable();
            $table->float('air_pressure_sea_level')->nullable();
            $table->float('visibility')->nullable();
            $table->float('wind_speed')->nullable();
            $table->float('percipation')->nullable();
            $table->float('snow_depth')->nullable();
            $table->string('conditions', 6)->nullable();
            $table->float('cloud_cover')->nullable();
            $table->integer('wind_direction')->nullable();

            $table->index('station', 'idx_measurement_station');

            $table->foreign('station', 'fk_measurement_station')
                ->references('name')
                ->on('station')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->charset = 'utf16';
            $table->collation = 'utf16_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement');
    }
};
