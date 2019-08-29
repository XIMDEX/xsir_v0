<?php

namespace Ximdex\Core\Utils;

use DateTime;
use Carbon\Carbon;

class DateHelpers
{
    public static function parse($date)
    {
        if ($date instanceof DateTime) {
            $dt = $date;
        } elseif (is_int($date)) {
            $dt = Carbon::now();
            $dt->timestamp = $date;
        } else {
            $dt = Carbon::parse($date);
        }
        return $dt->format(config('xfind.date.format', DateTime::ISO8601));
    }
}
