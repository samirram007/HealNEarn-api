<?php

namespace App\Enums;

enum UserTypeEnum: string
{
    case ADMIN = 'admin';
    case DEVELOPER = 'developer';
    case MEMBER = 'member';
    case SUPER_ADMIN = 'super_admin';
    case MANAGER = 'manager';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::DEVELOPER => 'Developer',
            self::MEMBER => 'Member',
            self::SUPER_ADMIN => 'Super Admin',
            self::MANAGER => 'Manager',
            
        };
    }

    public static function default(): string
    {
        return UserTypeEnum::ADMIN->value;
    }

    public static function labels(): array
    {
        return array_reduce(self::cases(), function ($items, UserTypeEnum $item) {
            $items[$item->value] = $item->label();

            return $items;
        }, []);
    }

    public static function dataLabels(): array
    {
        return array_reduce(self::cases(), function ($items, UserTypeEnum $item) {
            $items[$item->value] = $item->name;

            return $items;
        }, []);
    }
}
