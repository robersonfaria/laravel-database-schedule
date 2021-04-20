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
        'command_custom',
        'params',
        'options',
        'expression',
        'even_in_maintenance_mode',
        'without_overlapping',
        'on_one_server',
        'webhook_before',
        'webhook_after',
        'email_output',
        'sendmail_error',
        'status',
        'run_in_background'
    ];

    protected $attributes = [
        'expression' => '* * * * *',
        'params' => '{}',
        'options' => '{}',
    ];

    protected $casts = [
        'params' => 'array',
        'options' => 'array',
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

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function mapArguments()
    {
        $mapedArguments = [
            array_map(function ($item) {
                if ($item['type'] === 'function') {
                    return eval("return ${item['value']}");
                }
                settype($item['value'], $item['type']);
                return $item['value'];
            }, $this->params ?? [])
        ];
        return array_filter($mapedArguments[0]);
    }

    public function mapOptions()
    {
        $str = '';
        if (isset($this->options) && count($this->options) > 0) {
            foreach ($this->options as $name => $option) {
                if ($option['type'] === 'function') {
                    $option['value'] = eval("return ${$option['value']}");
                } else {
                    settype($option['value'], $option['type']);
                }
                $str .= ' --' . $name . '=' . $option['value'];
            }
        }
        return $str;
    }
}
