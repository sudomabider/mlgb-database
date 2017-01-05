<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLeagueOnPlayerRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('player_records', function (Blueprint $table) {
            $table->renameColumn('league','league_slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('player_records', function (Blueprint $table) {
            $table->renameColumn('league_slug','league');
        });
    }
}
