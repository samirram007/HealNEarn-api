<?php
namespace App\Enums;


enum LanguageEnum:string
{
    case English = 'en';
    case Bengali = 'bn';
    case Hindi = 'hi';
    case Arabic = 'ar';
    case French = 'fr';
    case Spanish = 'es';
    case German = 'de';
    case Portuguese = 'pt';
    case Italian = 'it';
    case Russian = 'ru';



    public function label(): string
    {
        return match($this){
            self::English => 'English',
            self::Bengali => 'Bengali',
            self::Hindi => 'Hindi',
            self::Arabic => 'Arabic',
            self::French => 'French',
            self::Spanish => 'Spanish',
            self::German => 'German',
            self::Portuguese => 'Portuguese',
            self::Italian => 'Italian',
            self::Russian => 'Russian',
        };
    }
    public static function default(): string
    {
        return  LanguageEnum::Hindi->value;
    }
    public static function labels():array
    {
        return array_reduce(self::cases(),function($items, LanguageEnum $item){
            $items[$item->value] = $item->name;
            return $items;
        },[]);
    }
    public static function dataLabels():array
    {
        return array_reduce(self::cases(),function($items, LanguageEnum $item){
            $items[$item->value] = $item->name;
            return $items;
        },[]);
    }
}

