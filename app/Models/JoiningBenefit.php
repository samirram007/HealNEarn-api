<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoiningBenefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'sale_id',
        'level',
        'amount',
        'commission',
    ];
    public function user(){return $this->belongsTo(User::class);}
    public function parent(){return $this->belongsTo(User::class);}
    public function sale(){return $this->belongsTo(Sale::class);}
}
