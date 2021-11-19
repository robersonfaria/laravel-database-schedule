<?php

namespace RobersonFaria\DatabaseSchedule\View;

use Illuminate\Support\HtmlString;

class Helpers
{
    public static function buildHeader(): HtmlString
    {
        $header = '';
        foreach (['command', 'arguments', 'options', 'expression', 'created_at', 'updated_at', 'status', 'actions'] as $column) {
            $caption = static::highlight($column, trans("schedule::schedule.fields.$column"));
            $direction = request()->get('direction') === 'asc' ? 'desc' : 'asc';
            if ($column === 'arguments' || $column === 'actions') {
                $header .= sprintf('<th class="text-center text-nowrap">%s</th>', $caption);
            } else {
                $routeName = config('database-schedule.route.name', 'database-schedule') . '.index';
                $params = ['orderBy' => $column, 'direction' => $direction];
                $route = route($routeName, $params);
                $header .= sprintf('<th class="text-center text-nowrap"><a href="%s">%s</a></th>', $route, $caption);
            }
        }

        return new HtmlString($header);
    }


    private static function highlight($orderBy, $caption): string
    {
        if (request()->has('orderBy') && request()->get('orderBy') === $orderBy) {
            $direction = request()->get('direction') === 'asc' ? 'up' : 'down';
            return sprintf('%s<i class="bi bi-sort-alpha-%s"></i>', $caption, $direction);
        }

        return $caption;
    }
}
