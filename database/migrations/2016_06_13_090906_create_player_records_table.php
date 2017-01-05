<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePlayerRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_records', function (Blueprint $table) {
            $table->increments('id');

            $table->string('tag');
            
            $table->string('league');

            $table->unsignedSmallInteger('expLevel');
            $table->string('trophies');
            $table->unsignedTinyInteger('clanRank');
            $table->unsignedInteger('donations');
            $table->unsignedInteger('donationsReceived');

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
        Schema::dropIfExists('player_records');
    }
}
