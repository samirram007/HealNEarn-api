<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Manager\ManagerResource;
use App\Http\Resources\Member\MemberCollection;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserActivity\UserActivityResource;

use Illuminate\Http\Request;

class UserResource extends SuccessResource
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
            'parentId' => $this->parent_id,
            'managerId' => $this->manager_id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'userType' => $this->user_type,
            'role' => $this->user_type,
            'contactNo' => $this->contact_no,
            'status' => $this->status,
            'activationDate' => $this->activation_date,
            'manager' => new ManagerResource($this->whenLoaded('manager')),
            'parent' => new MemberResource($this->whenLoaded('parent')),
            'userActivity' =>  new UserActivityResource($this->whenLoaded('user_activity')),
            'children' => new MemberCollection($this->whenLoaded('children')),
            'childrenCount' => $this->childrenCount(),
            'createdAt' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updatedAt' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
