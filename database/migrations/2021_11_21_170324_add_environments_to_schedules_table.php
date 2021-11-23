<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnvironmentsToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Config::get('database-schedule.table.schedules', 'schedules'), function (Blueprint $table) {
            $table->string('environments')->after('expression')->nullable();
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
            $table->dropColumn('environments');
        });
    }
}
