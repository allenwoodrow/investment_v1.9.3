<?php declare(strict_types=1);

namespace App\Enums;

enum TransactionType: string
{
    case WALLET_DEPOSIT = 'wdeposit';
    case SUBSCRIPTION = 'sub';
    case ACCOUNT_WITHDRAWAL = 'awithdraw';
    case WALLET_WITHDRAWAL = 'withdraw';
    case PROFIT_PAY = 'payout';
    case TRADING_DEPOSIT = 'deposit';
    case TRADING_WITHDRAW = 't_withdraw';

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
