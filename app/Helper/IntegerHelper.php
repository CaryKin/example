<?php

namespace App\Helper;

class IntegerHelper
{
    public static function mode($number, $divisor)
    {
        return $number % $divisor;
    }
}
