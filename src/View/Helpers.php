<?php

namespace RobersonFaria\DatabaseSchedule\View;

use Illuminate\Support\HtmlString;
use RobersonFaria\DatabaseSchedule\Models\Schedule;

class Helpers
{
    private static $columns = [
        'command',
        'arguments',
        'options',
        'expression',
        'groups',
        'environments',
        'created_at',
        'updated_at',
        'status',
        'actions',
    ];

    public static function buildHeader(): HtmlString
    {
        $header = '<tr>';
        foreach (static::$columns as $column) {
            if ($column === 'groups' && config('database-schedule.enable_groups', false) === false) {
                continue;
            }
            $caption = static::highlight($column, trans("schedule::schedule.fields.$column"));
            $direction = session()->get(Schedule::SESSION_KEY_DIRECTION) === 'asc' ? 'desc' : 'asc';
            if ($column === 'arguments' || $column === 'actions') {
                $header .= sprintf('<th class="text-center text-nowrap">%s</th>', $caption);
            } else {
                $route = static::indexRoute(['orderBy' => $column, 'direction' => $direction]);
                $header .= sprintf('<th class="text-center text-nowrap"><a href="%s">%s</a></th>', $route, $caption);
            }
        }

        $header .= '</tr>';

        return new HtmlString($header);
    }

    public static function buildFilter(): HtmlString
    {
        $filter = '<tr>';
        $js = "document.getElementById('schedule-filter-form').submit();";

        $schedule = app(config('database-schedule.model'));

        $filters = session()->get(Schedule::SESSION_KEY_FILTERS);

        foreach (static::$columns as $column) {
            switch($column) {
                case 'arguments':
                    $content = '';
                    break;
                case 'actions':
                    $content = '<button form="schedule-filter-form" title="Filter" class="btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>&nbsp;';
                    $content .= '<button form="schedule-filter-reset" type="submit" title="Reset" class="btn-sm btn-success"><i class="bi bi-x-circle-fill"></i></button>';
                    break;
                case 'groups':
                    $content = view('schedule::dropdown', [
                        'name' => 'filters[groups]',
                        'js' => $js,
                        'options' => $schedule::getGroups(),
                        'selected' => $filters['groups'] ?? null,
                    ]);
                    break;
                case 'status':
                    $content = view('schedule::dropdown', [
                        'name' => 'filters[status]',
                        'js' => $js,
                        'options' => [
                            2 => trans('schedule::schedule.status.trashed'),
                            0 => trans('schedule::schedule.status.inactive'),
                            1 => trans('schedule::schedule.status.active'),
                        ],
                        'selected' => $filters['status'] ?? null,
                    ]);
                    break;
                default:
                    $content = sprintf(
                        '<input @blur="%s" value="%s" form="schedule-filter-form" type="text" class="form-control" name="filters[%s]">',
                        $js,
                        $filters[$column] ?? '',
                        $column);
                    break;
            }

            $filter .= sprintf('<th class="text-center">%s</th>', $content);
        }

        $filter .= '</tr>';

        return new HtmlString($filter);
    }

    public static function indexRoute(array $params = []): string
    {
        return route(config('database-schedule.route.name', 'database-schedule') . '.index', $params);
    }

    private static function highlight($orderBy, $caption): string
    {
        if ($orderBy === session()->get(Schedule::SESSION_KEY_ORDER_BY)) {
            $direction = session()->get(Schedule::SESSION_KEY_DIRECTION) === 'asc' ? 'up' : 'down';
            return sprintf('%s<i class="bi bi-sort-alpha-%s"></i>', $caption, $direction);
        }

        return $caption;
    }
}
