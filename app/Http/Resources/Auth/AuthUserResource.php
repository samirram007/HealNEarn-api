<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'userType' => $this->user_type,
            'role' => $this->user_type,
            'contactNo' => $this->contact_no,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
