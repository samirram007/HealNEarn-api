<?php

namespace App\Http\Resources\Sale;

use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Request;


class SaleResource extends SuccessResource
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
            'saleNo' => $this->sale_no,
            'saleDate' => $this->sale_date,
            'userId' => $this->user_id,
            'productId' => $this->product_id,
            'quantity' => $this->quantity,
            'rate' => $this->rate,
            'amount' => $this->amount,
            'isConfirm' => $this->is_confirm,
            'confirmationDate' => $this->confirmation_date,
            'confirmedById' => $this->confirmed_by_id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'note' => $this->note,
            'created_at' => $this->created_at?$this->created_at->format('Y-m-d H:i:s'):null,
            'updated_at' => $this->updated_at?$this->updated_at->format('Y-m-d H:i:s'):null,
        ];
    }
}
