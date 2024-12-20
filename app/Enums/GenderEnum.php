<?php
namespace App\Enums;

enum GenderEnum: string {

    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Male',
            self::FEMALE => 'Female',
            self::OTHER => 'Other',
        };
    }
    public static function default(): string
    {
        return GenderEnum::MALE->value;
    }
    public static function labels(): array
    {
        return array_reduce(self::cases(), function ($items, GenderEnum $item) {
            $items[$item->value] = $item->name;
            return $items;
        }, []);
    }
    public static function dataLabels(): array
    {
        return array_reduce(self::cases(), function ($items, GenderEnum $item) {
            $items[$item->value] = $item->name;
            // $items[$item->label()] = $item->name;
            return $items;
        }, []);
    }
}
