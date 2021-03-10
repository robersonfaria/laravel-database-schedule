<?php

namespace RobersonFaria\DatabaseSchedule\Models;

use Illuminate\Console\Scheduling\ManagesFrequencies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Schedule extends Model
{
    use ManagesFrequencies, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    protected $fillable = [
        'command',
        'params',
        'expression',
        'even_in_maintenance_mode',
        'without_overlapping',
        'on_one_server',
        'webhook_before',
        'webhook_after',
        'email_output',
        'sendmail_error',
        'status'
    ];

    protected $attributes = [
        'expression' => '* * * * *',
        'params' => '{}',
    ];

    protected $casts = [
        'params' => 'array',
    ];

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = Config::get('database-schedule.table.schedules', 'schedules');
    }

    public function histories()
    {
        return $this->hasMany(ScheduleHistory::class, 'schedule_id', 'id');
    }

    public function scopeActive($query){
        return $query->where('status', true);
    }
}
