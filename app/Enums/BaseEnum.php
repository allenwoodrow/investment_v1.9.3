<?php

namespace App\Enums;

class BaseEnum
{
    public static function fromString(string $value): ?string
    {
        return $value;
    }
    
    public static function isEqual(string $value1, string $value2): bool
    {
        return $value1 === $value2;
    }
}
