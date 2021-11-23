<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomCommand extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table(Config::get('database-schedule.table.schedules', 'schedules'), function (Blueprint $table) {
            $table->string('command_custom')->after('command')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table(Config::get('database-schedule.table.schedules', 'schedules'), function (Blueprint $table) {
            $table->dropColumn('command_custom');
        });
    }
}
