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
        '2026_03_09_000007_create_subscriptions_table' => 'subscriptions',
        '2026_03_09_000002_create_station_table' => 'stations',
    ];

    public function up(): void
    {
        Schema::createIfNotExists('subscription_station', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription');
            $table->string('station', 10);
            $table->primary(['subscription', 'station']);
            $table->index('station', 'idx_station_subscription');

            $table->foreign('subscription')
                ->references('id')
                ->on('subscriptions');

            $table->foreign('station')
                ->references('name')
                ->on('station');

            $table->charset = 'utf16';
            $table->collation = 'utf16_unicode_ci';

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_station');
    }
};
