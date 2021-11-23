<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptionsFieldSchedulesHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Config::get('database-schedule.table.schedule_histories', 'schedule_histories'), function (Blueprint $table) {
            $table->text('options')->after('output')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Config::get('database-schedule.table.schedule_histories', 'schedule_histories'), function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
}
