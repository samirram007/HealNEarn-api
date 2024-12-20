<?php
namespace App\Enums;


enum UserStatusEnum:string
{
    case ACTIVE='active';
    case INACTIVE='inactive';
    case DELETED='deleted';
    case BLOCKED='blocked';
    case SUSPENDED='suspended';
    public static function default(): string
    {
        return  UserStatusEnum::ACTIVE->value;
    }
    public function label(): string
    {
        return match($this){
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::DELETED => 'Deleted',
            self::BLOCKED => 'Blocked',
            self::SUSPENDED => 'Suspended',
        };
    }
    public static function labels():array
    {
        return array_reduce(self::cases(),function($items, UserStatusEnum $item){
            $items[$item->value] = $item->label();
            return $items;
        },[]);
    }
    public static function dataLabels():array
    {
        return array_reduce(self::cases(),function($items, UserStatusEnum $item){
            $items[$item->value] = $item->name;
            return $items;
        },[]);
    }
}
