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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('company')->nullable();
            $table->integer('type')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->float('price');
            $table->string('notes', 256)->nullable();
            $table->string('identifier', 45);
            $table->string('token', 100);

            $table->index('company', 'subscription_company_idx');
            $table->index('type', 'subscription_type_idx');
            $table->index('identifier', 'subscription_identifier');

            $table->foreign('type')
                ->references('id')
                ->on('subscription_types');

            $table->foreign('company')
                ->references('id')
                ->on('companies');
        });
    }
//id INT
//identifier VARCHAR(45)
//endpoint_used VARCHAR(256)
//files_downloaded INT
//activity_date DATE
//activity_time TIME
//authorized TINYINT
//data_transferred INT

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
