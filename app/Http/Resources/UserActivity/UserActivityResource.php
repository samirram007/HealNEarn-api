<?php

namespace App\Http\Resources\UserActivity;

use App\Http\Resources\User\UserResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Request;

class UserActivityResource extends SuccessResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'immediateCount'=> $this->immediate_count,
            'immediateBusiness'=>$this->immediate_business,
            'teamCount'=>$this->team_count,
            'teamBusiness'=>$this->team_business,
            'totalCount'=>$this->total_count,
            'totalBusiness'=>$this->total_business,
            'joiningBenefit'=>$this->joining_benefit,
            'poolIncome'=>$this->pool_income,
            'selfEarning'=>$this->self_earning,
            'selfPaid'=>$this->self_paid,
            'teamEarning'=>$this->team_earning,
            'teamPaid'=>$this->team_paid,
            'totalEarning'=>$this->total_earning,
            'totalPaid'=>$this->total_paid,
            'selfBalance'=>$this->self_balance,
            'teamBalance'=>$this->team_balance,
            'totalBalance'=>$this->total_balance,
            'lastPaymentDate'=>$this->last_payment_date,
            'user' => new UserResource($this->whenLoaded('user')),

           ];
    }
}
