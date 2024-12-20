<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class SuccessResource extends JsonResource{
    public function toArray(Request $request)
    {
        return parent::toArray($request);
    }
    public function with(Request $request): array
    {
        return [
            'status' => true,
            'code' => 200,
            'message'=> 'Record Updated'
        ];
    }
}
