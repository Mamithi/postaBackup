<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('InvoiceNo');
            $table->string('InvoiceDate');
            $table->string('DepatureDate');
            $table->string('ArrivalDate');
            $table->string('Conveyance');
            $table->string('BlNo');
            $table->string('CountryTo');
            $table->string('PortTo');
            $table->string('CountryFrom');
            $table->string('PortFrom');
            $table->string('ViaPort');
            $table->string('PortNumber')->nullable();
            $table->string('CountryVia1')->nullable();
            $table->string('PortVia1')->nullable();
            $table->string('CountryVia2')->nullable();
            $table->string('PortVia2')->nullable();
            $table->string('CountryVia3')->nullable();
            $table->string('PortVia3')->nullable();
            $table->string('CountryVia4')->nullable();
            $table->string('PortVia4')->nullable();
            $table->string('CountryVia5')->nullable();
            $table->string('PortVia5')->nullable();
            $table->string('BasisValuation');
            $table->string('Consignee');
            $table->string('Vessel');
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
        Schema::drop('shippings');
    }
}
