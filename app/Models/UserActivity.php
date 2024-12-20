<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'immediate_count',
        'immediate_business',
        'team_count',
        'team_business',
        'total_count',
        'total_business',
        'joining_benefit',
        'pool_income',
        'self_earning',
        'self_paid',
        'team_earning',
        'team_paid',
        'total_earning',
        'total_paid',
        'self_balance',
        'team_balance',
        'total_balance',
        'last_payment_date',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
