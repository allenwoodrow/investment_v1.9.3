<?php declare(strict_types=1);

namespace App\Enums;

enum SubscriptionStatus: string
{
    case RUNNING = 'running';
    case CANCELLED = 'cancelled';
    case SUSPENDED = 'suspended';
    case COMPLETE = 'complete';
    
    /**
     * Get all values as an array
     * 
     * @return array
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    /**
     * Convert a string value to enum instance if valid
     * 
     * @param string $value
     * @return self|null
     */
    public static function fromString(string $value): ?self
    {
        return self::tryFrom($value);
    }
    
    /**
     * Check if two values are equal
     * 
     * @param string $value1
     * @param string $value2
     * @return bool
     */
    public static function isEqual(string $value1, string $value2): bool
    {
        $enum1 = self::tryFrom($value1);
        $enum2 = self::tryFrom($value2);
        
        return $enum1 === $enum2;
    }
}
