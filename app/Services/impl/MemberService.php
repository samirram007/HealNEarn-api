<?php

namespace App\Services\impl;

use App\Enums\UserTypeEnum;
use App\Exceptions\ModelNotFoundException as ExceptionsModelNotFoundException;
use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Http\Resources\Member\MemberCollection;
use App\Http\Resources\Member\MemberResource;
use App\Models\User as Member;
use App\Services\IMemberService;
use Exception;

class MemberService implements IMemberService
{
    protected $resourceLoader;

    public function __construct()
    {
        $this->resourceLoader = [

            'parent',
            'manager',
             'user_activity',
               ];
    }

    public function getAll()
    {
        $members = Member::all();
        $user = auth()->user();
        if ($user->user_type == UserTypeEnum::ADMIN) {
            $members = Member::with($this->resourceLoader)->where('user_type', UserTypeEnum::MEMBER)->get();
        } elseif ($user->user_type == UserTypeEnum::MANAGER) {
            $members = Member::with($this->resourceLoader)
                ->where('manager_id', $user->id)
                ->where('user_type', UserTypeEnum::MEMBER)
                ->get();
        } elseif ($user->user_type == UserTypeEnum::MEMBER) {
            $members = Member::with($this->resourceLoader)
                ->where('parent_id', $user->id)
                ->where('user_type', UserTypeEnum::MEMBER)->get();
        }

        if ($members->isEmpty()) {
            return response()->json([
                'message' => 'No records found.',
            ], 404); // Return 404 status code
        }
// dd($members->toArray());
        return MemberCollection::make($members);

    }

    public function getByUsername($username)
    {
        // dd($username);
        try {
            $member = Member::with($this->resourceLoader)
                ->where('username', $username)->first();
            $user = auth()->user();
            if ($member == null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Record not found.',
                    'code' => 404,
                ], 404);
            }

            return MemberResource::make($member);
        } catch (Exception $e) {
            // Handle the case where the model is not found
            // throw new ExceptionsModelNotFoundException($e);
            // return new ExceptionsModelNotFoundException($e);

            return response()->json([
                'status' => false,
                'message' => 'Record not found.' ,
                'code' => 404,
            ], 404);
        }
        // if ($user->user_type == UserTypeEnum::ADMIN) {
        //     $members = Member::with($this->resourceLoader)->where('user_type', UserTypeEnum::MEMBER)->get();
        // }

    }

    public function getById($id)
    {

        try {
            $response = Member::with($this->resourceLoader)->where('user_type', UserTypeEnum::MEMBER)->findOrFail($id);

            return MemberResource::make($response);
        } catch (Exception $e) {
            // Handle the case where the model is not found
            // throw new ExceptionsModelNotFoundException($e);
            // return new ExceptionsModelNotFoundException($e);
            //dd($e);
            return response()->json([
                'status' => false,
                'message' => 'Record not found.',
                'code' => 404,
            ], 404);
        }
    }

    public function store(StoreMemberRequest $request)
    {
        //dd($request->validated());
        $response = Member::create($request->validated());

        return MemberResource::make($response);
    }

    public function update(UpdateMemberRequest $request, $id)
    {
        $response = Member::find($id)->update($request->validated());

        return MemberResource::make($response);
    }

    public function delete($id)
    {
        Member::find($id)->delete();

        return response()->noContent();
    }

    public function profile()
    {
        $response = auth()->user();

        return MemberResource::make($response);
    }
}
