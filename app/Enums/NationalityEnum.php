<?php
namespace App\Enums;


enum NationalityEnum:string
{

    case INDIAN='indian';
    case BANGLADESHIS = 'bangladeshis';
    case BHUTANESE = 'bhutanese';
    case NEPALESE = 'nepalese';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this){
            self::INDIAN => 'Indian',
            self::BANGLADESHIS => 'Bangladeshis',
            self::BHUTANESE => 'Bhutanese',
            self::NEPALESE => 'Nepalese',
            self::OTHER => 'Other',
        };
    }
    public static function default(): string
    {
        return  NationalityEnum::INDIAN->value;
    }
    public static function labels():array
    {
        return array_reduce(self::cases(),function($items, NationalityEnum $item){
            $items[$item->value] = $item->name;
            return $items;
        },[]);
    }
    public static function dataLabels():array
    {
        return array_reduce(self::cases(),function($items, NationalityEnum $item){
            $items[$item->value] = $item->name;
            return $items;
        },[]);
    }
}

