<?php

if (!function_exists('roundUp')) {
    function roundUp($number, $precision = 2)
    {
        $fig = pow(10, $precision);
        return (ceil($number * $fig) / $fig);
    }
}
if (!function_exists('roundUpPercent')) {
    function roundUpPercent($number, $percent, $precision = 2)
    {
        return roundUp($number * $percent / 100, $precision);
    }
}
