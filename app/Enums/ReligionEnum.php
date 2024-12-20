<?php
namespace App\Enums;


enum ReligionEnum:string
{
    case HINDU='hindu';
    case MUSLIM='muslim';
    case SIKH='sikh';
    case CHRISTIAN='christian';
    case BUDDHIST='buddhist';
    case JAIN='jain';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this){
            self::HINDU => 'Hindu',
            self::MUSLIM => 'Muslim',
            self::SIKH => 'Sikh',
            self::CHRISTIAN => 'Christian',
            self::BUDDHIST => 'Buddhist',
            self::JAIN => 'Jain',
            self::OTHER => 'Other',

        };
    }
    public static function default(): string
    {
        return  ReligionEnum::HINDU->value;
    }
    public static function labels():array
    {
        return array_reduce(self::cases(),function($items, ReligionEnum $item){
            $items[$item->value] = $item->name;
            return $items;
        },[]);
    }
    public static function dataLabels():array
    {
        return array_reduce(self::cases(),function($items, ReligionEnum $item){
            $items[$item->value] = $item->name;
            return $items;
        },[]);
    }
}

