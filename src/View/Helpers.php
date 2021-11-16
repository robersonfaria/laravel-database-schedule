<?php

namespace RobersonFaria\DatabaseSchedule\View;

class Helpers
{
    public static function highlight($orderBy, $caption)
    {
        if (request()->has('orderBy') && request()->get('orderBy') === $orderBy) {
            $dir = strpos(url()->previous(), $orderBy) !== false ? 'up' : 'down';
            return $caption . '<i class="bi bi-sort-alpha-'.$dir.'"></i>';
        }

        return $caption;
    }
}
