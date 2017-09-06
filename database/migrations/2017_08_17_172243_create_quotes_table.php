<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('InvoiceNo');
            $table->string('InvoiceDate');
            $table->string('DepatureDate');
            $table->string('ArrivalDate');
            $table->string('ToPort');
            $table->string('FromPort');
            $table->string('Vessel');
            $table->string('Name');
            $table->string('Type');
            $table->string('Phone');
            $table->string('Email');
            $table->string('Address');
            $table->string('Currency');
            $table->string('Sum');
            $table->string('Premium');
            $table->string('Duty');
            $table->string('Net');
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
        Schema::drop('quotes');
    }
}
