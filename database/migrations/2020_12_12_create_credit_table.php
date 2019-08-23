<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('external_id');
            $table->integer('period');
			$table->double('total');
			$table->string('request_number');
			$table->double('invested_amount');
			$table->integer('type');
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
         Schema::drop('credit');
    }
}
