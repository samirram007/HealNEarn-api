<?php

namespace App\Services\impl;

use App\Enums\UserTypeEnum;
use App\Exceptions\ModelNotFoundException as ExceptionsModelNotFoundException;
use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Http\Resources\Member\MemberCollection;
use App\Http\Resources\Member\MemberResource;
use App\Models\Sale;
use App\Models\User as Member;
use App\Models\UserActivity;
use App\Services\IMemberService;
use Exception;
use Illuminate\Support\Facades\DB;

class MemberService implements IMemberService
{
    protected $resourceLoader;

    public function __construct()
    {
        $this->resourceLoader = [

            'parent',
            'manager',
            'user_activity',
            'sales.product',
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
                'message' => 'Record not found.',
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
    public function getMemberChildren($id)
    {

        try {
            $response = Member::with('descendants', 'user_activity')->findOrFail($id);

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
    public function getMemberSale($id)
    {
        try {
            $response = Member::with('user_activity','sales.product')->findOrFail($id);

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
    public function getMemberEarning($id)
    {
        try {
            $response = Member::with('user_activity')->findOrFail($id);

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
    public function getMemberPayment($id)
    {
        try {
            $response = Member::with('payments', 'user_activity')->findOrFail($id);

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
        DB::beginTransaction();

        try {
        // Extract validated data
        $validatedData = $request->validated();

        // Extract sale data
        $saleData = $validatedData['sale'] ?? null;

        // Remove sale from member data to create the user
        unset($validatedData['sale']);

        // Create the member
        $member = Member::create($validatedData);

        // If sale data exists, associate it with the created member
        if ($saleData) {
            $saleData['user_id'] = $member->id; // Add user_id to sale
            $sale = Sale::create($saleData); // Assuming you have a `Sale` model
        }
        $userActivityExists = UserActivity::where('user_id', $member->id)->exists();

        if (!$userActivityExists) {
            $userActivity = new UserActivity();
            $userActivity->user_id = $member->id;
            $userActivity->save();
        }
        DB::commit();
        return MemberResource::make($member);
    } catch (Exception $e) {
        // If an error occurs, roll back the transaction
        DB::rollBack();

        // Return an error response
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong. Please try again.',
            'error' => $e->getMessage(),
        ], 500);
    }
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
