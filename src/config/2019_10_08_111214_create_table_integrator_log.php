<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIntegratorLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrator_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('date');
            $table->string('api');
            $table->text('url');
            $table->string('method');
            $table->longText('request');
            $table->integer('duration_in_seconds');
            $table->integer('response_code');
            $table->longText('response_body');
        });

        Schema::create('retry_integrator_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('date');
            $table->bigInteger('integrator_log_id');
            $table->integer('duration_in_seconds');
            $table->integer('response_code');
            $table->longText('response_body');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integrator_log');
        Schema::dropIfExists('retry_integrator_log');
    }
}
