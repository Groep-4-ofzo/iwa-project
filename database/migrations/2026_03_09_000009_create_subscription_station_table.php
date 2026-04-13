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
        Schema::create('subscription_station', function (Blueprint $table) {
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
