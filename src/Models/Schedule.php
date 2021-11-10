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
        'sendmail_success',
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

    public function getParameters(): array
    {
        return $this->getArguments() + $this->getOptions();
    }

    private function getArguments(): array
    {
        $arguments = [];

        foreach (($this->params ?? []) as $argument => $value) {
            if(empty($value['value'])) {
                continue;
            }
            if (isset($value["type"]) && $value['type'] === 'function') {
                $arguments[$argument] = (string) $value['value']();
            } else {
                $arguments[$argument] = $value['value'];
            }
        }

        return $arguments;
    }

    private function getOptions(): array
    {
        $options = [];
        foreach (($this->options ?? []) as $option => $value) {
            if(is_array($value) && ($value['value'] ?? null) === null) {
                continue;
            }
            $option = '--' . $option;
            if(is_array($value)) {
                if (isset($value["type"]) && $value['type'] === 'function') {
                    $options[$option] = (string) $value['value']();
                } else {
                    $options[$option] = $value['value'];
                }
            } else {
                $options[] = $option;
            }
        }

        return $options;
    }
}
