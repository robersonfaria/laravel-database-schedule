<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogSuccessAndLogErrorToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Config::get('database-schedule.table.schedules', 'schedules'), function (Blueprint $table) {
            $table->boolean('log_success')->after('sendmail_error')->default(true);
            $table->boolean('log_error')->after('log_success')->default(true);
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
            $table->dropColumn('log_success');
            $table->dropColumn('log_error');
        });
    }
}
