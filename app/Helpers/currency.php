<?php

use Illuminate\Support\Str;

if (! function_exists('currency')) {
    /**
     * @param  float|int|string|null  $amount
     */
    function currency(float|int|string|null $amount, ?int $decimals = null): string
    {
        if ($amount === null || $amount === '') {
            return "\u{2014}";
        }

        $c = config('currency', []);
        $dec = $decimals ?? (int) ($c['decimal_places'] ?? 0);
        $sep = (string) ($c['thousands_separator'] ?? ',');
        $sym = (string) ($c['symbol'] ?? '');
        $pos = Str::lower((string) ($c['position'] ?? 'after'));

        $num = number_format((float) $amount, $dec, '.', $sep);

        return $pos === 'before' ? ($sym.$num) : trim($num.' '.$sym);
    }
}
