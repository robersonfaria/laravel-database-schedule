<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRunInBackgroundTableSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Config::get('database-schedule.table.schedules', 'schedules'), function (Blueprint $table) {
            $table->boolean('run_in_background')->after('status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Config::get('database-schedule.table.schedules', 'schedules'), function (Blueprint $table) {
            $table->dropColumn('run_in_background');
        });
    }
}
