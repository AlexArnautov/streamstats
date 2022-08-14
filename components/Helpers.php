<?php

declare(strict_types=1);

namespace app\components;

use Exception;

class Helpers
{
    /**
     * Calculate median for array
     * @throws Exception
     */
    public static function calculateMedian(array $array): int|float
    {
        if (!$array) {
            throw new Exception('Cannot calculate median because Argument #1 ($array) is empty');
        }
        sort($array);
        $middleIndex = count($array) / 2;
        if (is_float($middleIndex)) {
            return $array[(int)$middleIndex];
        }
        return ($array[$middleIndex] + $array[$middleIndex - 1]) / 2;
    }
}