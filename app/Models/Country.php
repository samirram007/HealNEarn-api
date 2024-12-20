<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasApiTokens,HasFactory;
    protected $fillable = [
        'name',
        'country_code'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
