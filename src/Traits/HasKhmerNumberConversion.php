<?php

namespace Asorasoft\Chhankitek\Traits;

use Asorasoft\Chhankitek\Constant;

trait HasKhmerNumberConversion
{
    /**
     * @param int $number
     * @return string
     */
    public function convertToKhmerNumber(int $number)
    {
        $constant = new Constant();
        $str_numbers = (string)$number;

        foreach ($constant->khmerNumbers as $key => $value) {
            $str_numbers = str_replace($key, $value, $str_numbers);
        }
        return trim($str_numbers);
    }
}
