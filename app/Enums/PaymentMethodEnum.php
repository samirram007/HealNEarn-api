<?php

namespace App\Enums;

enum PaymentMethodEnum: string
{
    case CASH = 'cash';
    case ONLINE = 'online';
    case CHEQUE = 'cheque';
    case RTGS = 'rtgs';
    case NEFT = 'neft';
    case NEFT_RTGS = 'neft_rtgs';
    case BANK_TRANSFER = 'bank_transfer';
    case BANK_DEPOSIT = 'bank_deposit';
    case UPI = 'upi';
    case PARENT_MEMBER = 'parent_member';

    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::CHEQUE => 'Cheque',
            self::ONLINE => 'Online',
            self::RTGS => 'RTGS',
            self::NEFT => 'NEFT',
            self::NEFT_RTGS => 'NEFT/RTGS',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::BANK_DEPOSIT => 'Bank Deposit',
            self::UPI => 'UPI',
            self::PARENT_MEMBER => 'Parent Member',
            self::OTHER => 'Other',

        };
    }

    public static function default(): string
    {
        return PaymentMethodEnum::CASH->value;
    }

    public static function labels(): array
    {
        return array_reduce(self::cases(), function ($items, PaymentMethodEnum $item) {
            $items[$item->value] = $item->label();

            return $items;
        }, []);
    }

    public static function dataLabels(): array
    {
        return array_reduce(self::cases(), function ($items, PaymentMethodEnum $item) {
            $items[$item->value] = $item->name;

            return $items;
        }, []);
    }
}
