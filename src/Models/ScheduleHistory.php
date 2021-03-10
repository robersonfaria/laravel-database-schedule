<?php

namespace RobersonFaria\DatabaseSchedule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ScheduleHistory extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    protected $fillable = [
        'command',
        'params',
        'output',
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

        $this->table = Config::get('database-schedule.table.schedule_histories', 'schedule_histories');
    }

    public function command()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }
}
