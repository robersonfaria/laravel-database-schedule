<?php

namespace RobersonFaria\DatabaseSchedule\View;

class Helpers
{
    public static function buildHeader()
    {
        $route = route(config('database-schedule.route.name', 'database-schedule') . '.index');

        $header = '';

        foreach (['command', 'arguments', 'options', 'expression', 'created_at', 'updated_at', 'status', 'actions'] as $column) {
            $caption = static::highlight($column, trans("schedule::schedule.fields.$column"));
            $otherDirection = request()->get('direction') === 'asc' ? 'desc' : 'asc';
            if ($column === 'arguments' || $column === 'actions') {
                $header .= "<th class=\"text-center text-nowrap\">$caption</th>";
            } else {
                $header .= "<th class=\"text-center text-nowrap\"><a href=\"$route?orderBy=$column&direction=$otherDirection\">$caption</a></th>";
            }
        }

        return $header;
    }


    public static function highlight($orderBy, $caption)
    {
        if (request()->has('orderBy') && request()->get('orderBy') === $orderBy) {
            $dir = request()->get('direction') === 'asc' ? 'up' : 'down';
            return $caption . '<i class="bi bi-sort-alpha-'.$dir.'"></i>';
        }

        return $caption;
    }
}
