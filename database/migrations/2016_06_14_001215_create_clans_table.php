<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clans', function (Blueprint $table) {
            $table->increments('id');

            $table->string('tag');
            $table->string('name');
            $table->string('type');
            $table->text('description');
            $table->string('badge_small')->nullable();
            $table->string('badge_medium')->nullable();
            $table->string('badge_large')->nullable();
            $table->unsignedTinyInteger('clanLevel');
            $table->unsignedInteger('clanPoints');
            $table->unsignedSmallInteger('warWinStreak');
            $table->unsignedSmallInteger('warWins');
            $table->unsignedSmallInteger('warTies');
            $table->unsignedSmallInteger('warLosses');
            $table->unsignedTinyInteger('members');

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
        Schema::dropIfExists('clans');
    }
}
