<?php

namespace App\Http\Resources\Payment;

use App\Http\Resources\SuccessResource;
use Illuminate\Http\Request;


class PaymentResource extends SuccessResource
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
            'name' => $this->name,
            'created_at' => $this->created_at?$this->created_at->format('Y-m-d H:i:s'):null,
            'updated_at' => $this->updated_at?$this->updated_at->format('Y-m-d H:i:s'):null,
        ];
    }
}
