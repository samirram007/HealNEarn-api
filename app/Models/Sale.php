<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable=[
        'sale_no',
        'sale_date',
        'user_id',
        'product_id',
        'quantity',
        'rate',
        'amount',
        'is_confirm',
        'confirmed_by_id',
        'confirmed_date',
        'note',
    ];

    public function product(){
       return $this->belongsTo(Product::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function confirm_by(){
        return $this->belongsTo(User::class);
    }
}
