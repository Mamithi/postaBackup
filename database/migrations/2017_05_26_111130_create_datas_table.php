<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Currency');
            $table->string('Transport');
            $table->string('Transit');
            $table->string('Containerized');
            $table->string('Category');
            $table->string('Sum');
            $table->string('Premium');
            $table->string('Net');
            $table->string('Duty');
            $table->string('Levy');
            $table->string('Total');
            $table->string('PersonId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('datas');
    }
}
