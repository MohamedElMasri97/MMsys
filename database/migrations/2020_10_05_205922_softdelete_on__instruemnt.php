<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SoftdeleteOnInstruemnt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::table('instruments_messages', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::table('results', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::table('samples', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('instruments', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });


        Schema::table('samples', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
}
