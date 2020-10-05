<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Null_;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instrument_id');
            $table->foreign('instrument_id')->references('id')->on('instruments');
            $table->unsignedBigInteger('sample_id');
            $table->foreign('sample_id')->references('id')->on('samples');
            $table->string('unit')->default('Undefinaed');
            $table->string('result');
            $table->string('testName')->default('Undefined');
            $table->string('testCode');
            $table->string('sampleType')->default('Undefinaed');
            $table->string('abnormality')->default('Undefined');
            $table->string('range')->default('Undefined');
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
        Schema::dropIfExists('results');
    }
}
