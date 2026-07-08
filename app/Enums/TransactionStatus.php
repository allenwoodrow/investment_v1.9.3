<?php declare(strict_types=1);

namespace App\Enums;

enum TransactionStatus: string
{
    case WAITING = 'waiting';
    case CONFIRMING = 'confirming';
    case CONFIRMED = 'confirmed';
    case SENDING = 'sending';
    case FINISHED = 'finished';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case EXPIRED = 'expired';
    case PARTIALY_PAID = 'partialy_paid';

    public static function fromString(string $value): ?self
    {
        return self::tryFrom($value);
    }

    public static function isEqual(string|self $value1, string|self $value2): bool
    {
        $enum1 = $value1 instanceof self ? $value1 : self::tryFrom($value1);
        $enum2 = $value2 instanceof self ? $value2 : self::tryFrom($value2);

        return $enum1 === $enum2;
    }
}
