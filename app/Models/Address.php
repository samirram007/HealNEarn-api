<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_type',
        'address_line_1',
        'address_line_2',
        'city',
        'post_office',
        'rail_station',
        'police_station',
        'postal_code',
        'latitude',
        'longitude',
        'country_id',
        'state_id',
    ];
}
