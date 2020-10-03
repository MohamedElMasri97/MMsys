<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefinstrumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refinstruments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('command');
            $table->string('company');
            $table->string('protocol');
            $table->string('defport')->nullable();
            $table->string('commtype')->nullable();
            $table->string('boudrate')->nullable();
            $table->string('stopbit')->nullable();
            $table->string('parity')->nullable();
            $table->string('bitlength')->nullable();
            $table->string('pythonpath');
            $table->string('imagepath');
            $table->text('information')->nullable();
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
        Schema::dropIfExists('refinstruments');
    }
}
