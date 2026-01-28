<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Traits;

use Asorasoft\Chhankitek\Calendar\Constant;

trait HasKhmerNumberConversion
{
    /**
     * Convert a number to its Khmer numeral representation.
     */
    public function convertToKhmerNumber(int|string $number): string
    {
        $constant = new Constant;
        $strNumbers = (string) $number;

        foreach ($constant->khmerNumbers as $key => $value) {
            $strNumbers = str_replace((string) $key, $value, $strNumbers);
        }

        return mb_trim($strNumbers);
    }
}
