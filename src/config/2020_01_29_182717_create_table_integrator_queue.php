<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIntegratorQueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrator_queue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('date');
            $table->string('action');
            $table->longText('data');
            $table->boolean('blocked')->default(false);
        });

        Schema::create('integrator_queue_failed', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('date');
            $table->string('action');
            $table->longText('data');
            $table->text('response');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integrator_queue');
        Schema::dropIfExists('integrator_queue_failed');
    }
}
