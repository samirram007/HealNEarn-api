<?php

namespace App\Enums;

enum EarningTypeEnum: string
{
    case LEVEL = 'level';
    case POOL = 'pool';

    public function label(): string
    {
        return match ($this) {
            self::LEVEL => 'Level',
            self::POOL => 'Pool',

        };
    }

    public static function default(): string
    {
        return EarningTypeEnum::LEVEL->value;
    }

    public static function labels(): array
    {
        return array_reduce(self::cases(), function ($items, EarningTypeEnum $item) {
            $items[$item->value] = $item->label();

            return $items;
        }, []);
    }

    public static function dataLabels(): array
    {
        return array_reduce(self::cases(), function ($items, EarningTypeEnum $item) {
            $items[$item->value] = $item->name;

            return $items;
        }, []);
    }
}
