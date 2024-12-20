<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SuccessCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return parent::toArray($request);
    }
    public function with(Request $request): array
    {
        return [
            'status' => true,
            'code' => 200,
            'message'=> count($this). ' Record(s) fetched'
        ];
    }
}
