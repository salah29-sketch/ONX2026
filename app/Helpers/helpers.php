<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

if (!function_exists('str')) {
    function str($string)
    {
        return Str::of($string);
    }
}

/**
 * تاريخ بالعربي مع اسم اليوم (مثلاً: الخميس 12 مارس 2025)
 */
if (!function_exists('ar_date')) {
    function ar_date($date, string $format = 'l d F Y'): string
    {
        $d = $date instanceof \DateTimeInterface
            ? Carbon::parse($date)
            : Carbon::parse((string) $date);
        return $d->locale('ar')->translatedFormat($format);
    }
}