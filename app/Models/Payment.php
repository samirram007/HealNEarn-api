<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable=[
        'payment_date',
        'user_id',
        'manager_id',
        'amount',
        'payment_method',
        'note',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function manager()
    {
        return $this->belongsTo(User::class);
    }

}
