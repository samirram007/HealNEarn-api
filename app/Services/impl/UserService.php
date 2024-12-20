<?php

namespace App\Services\impl;

use App\Enums\UserTypeEnum;
use App\Exceptions\ModelNotFoundException as ExceptionsModelNotFoundException;
use App\Http\Requests\User\PasswordChangeRequest;
use App\Http\Requests\User\StatusChangeRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\Member\MemberCollection;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\JoiningBenefit;
use App\Models\PoolIncome;
use App\Models\User;
use App\Models\UserActivity;
use App\Services\IUserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class UserService implements IUserService
{
    protected $resourceLoader;
    protected $selectedParentList;

    public function __construct()
    {
        $this->resourceLoader = [
            //buildings order by id desc
            'parent',
            'manager',
        ];
        $this->selectedParentList = [];
    }
    public function addParent($currentParent)
    {
        $this->selectedParentList[] = ["id" => $currentParent->id];
    }

    public function getSelectedParentList()
    {
        return $this->selectedParentList;
    }
    public function getAll()
    {

        $users = User::with($this->resourceLoader)->all();

        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No records found.',
            ], 404); // Return 404 status code
        }

        return UserCollection::make($users);
    }

    public function getById(int $id)
    {

        try {
            $response = User::with($this->resourceLoader)->findOrFail($id);

            return UserResource::make($response);
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
    }

    public function store(StoreUserRequest $request)
    {
        $response = User::create($request->validated());

        return UserResource::make($response);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $response = User::find($id)->update($request->validated());

        return UserResource::make($response);
    }

    public function delete(int $id)
    {
        User::find($id)->delete();

        return response()->noContent();
    }

    public function profile()
    {

        $user = auth()->user();

        return UserResource::make($user);
    }

    public function passwordChange(PasswordChangeRequest $passwordChangeRequest)
    {
        $data = $passwordChangeRequest->validated();
        /**
         * @var User $user
         */
        $user = auth()->user();
        $user->password = bcrypt($data['new_password']);
        $user->update();

        return response()->json([
            'status' => true,
            'message' => 'password changed successfully',
        ]);
        // $response = User::find($id)->update($request->validated());
    }

    public function statusChange(StatusChangeRequest $statusChangeRequest)
    {
        // UserActivity::truncate();
        // JoiningBenefit::truncate();
        DB::beginTransaction();

        try {
            $data = $statusChangeRequest->validated();

            $user = User::find($data['user_id']);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ], 404);
            }

            // Update user status and other fields
            $user->status = $data['status'];
            $user->product_id = $data['product_id'] ?? 1;

            // Handle purchase date
            $purchaseDate = isset($data['purchase_date'])
                ? Carbon::parse($data['purchase_date']) // Convert timestamp
                : now();

            $user->purchase_date = $purchaseDate->toDateTimeString();
            $user->product_no = $data['product_no'];
            $user->product_amount = $data['product_amount'] ?? 300;

            // Save the changes
            $user->update();

            //Distribute Commission To Parent upto 7 step

            // Commit the transaction
            DB::commit();
            // dd($user);
            $existInDistribution = JoiningBenefit::where('user_id', $user->id)->first();
            if (!$existInDistribution) {
                dd('');
            }
            $this->distributeJoiningCommission($user);
            $this->poolIncome();
            return response()->json(['data' => $this->selectedParentList]);
            // Return response with the updated user data
            // return response()->json([
            //     'status' => true,
            //     'message' => 'Status changed successfully',
            //     'data' => new UserResource($user),
            // ]);
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

    private function distributeJoiningCommission(User $user)
    {
        $commissionRates = [
            1 => 30, // Level 1
            2 => 15, // Level 2
            3 => 10, // Level 3
            4 => 5,  // Level 4
            5 => 5,  // Level 5
            6 => 5,  // Level 6
            7 => 5,  // Level 7
        ];

        $currentParent = $user->parent; // Start with the immediate parent
        $level = 1;
        $teamEarning = 0;
        while ($currentParent) {
            // Calculate commission
            $commission = 0; // Assuming `joining_amount` is on the user model
            //dump($currentParent->user_type);
            if (in_array($currentParent->user_type, [UserTypeEnum::ADMIN])) {
                $commission = 0;
            } elseif (in_array($currentParent->user_type, [UserTypeEnum::MANAGER])) {
                $commission = 30;
                JoiningBenefit::create([
                    'user_id' => $user->id,       // ID of the user whose commission is being distributed
                    'parent_id' => $currentParent->id, // Parent receiving the commission
                    'level' => $level,           // Level of hierarchy
                    'amount' => $user->product_amount, // Original amount
                    'commission' => $commission, // Calculated commission
                ]);
            } elseif (in_array($currentParent->user_type, [UserTypeEnum::MEMBER])) {
                if ($level <= count($commissionRates)) {
                    $commission = $commissionRates[$level];
                    //this will store all parent temporarily

                    // Store the commission in the JoiningBenefit table
                    // JoiningBenefit::create([
                    //     'user_id' => $user->id,       // ID of the user whose commission is being distributed
                    //     'parent_id' => $currentParent->id, // Parent receiving the commission
                    //     'level' => $level,           // Level of hierarchy
                    //     'amount' => $user->product_amount, // Original amount
                    //     'commission' => $commission, // Calculated commission
                    // ]);
                }
            } else {
                $commission = 0;
            }

            $userActivity = $currentParent->user_activity;
            if (!$userActivity) {
                $userActivity = new UserActivity();
                $userActivity->user_id = $currentParent->id;
                if ($level == 1) {
                    $userActivity->immediate_count = 1;
                    $userActivity->immediate_business = $user->product_amount;
                }
                $userActivity->team_count = 1;
                $userActivity->team_business = $user->product_amount;
                $userActivity->total_count = 1;
                $userActivity->total_business = $user->product_amount;
                $userActivity->joining_benefit = $commission;
                $userActivity->self_earning = $commission;
                $userActivity->team_earning = $teamEarning;
                $userActivity->total_earning = ($teamEarning + $commission);
                $userActivity->self_balance = $commission;
                $userActivity->team_balance = $teamEarning;
                $userActivity->total_balance = ($teamEarning + $commission);
            } else {
                $userActivity->user_id = $currentParent->id;
                if ($level == 1) {
                    $userActivity->immediate_count += 1;
                    $userActivity->immediate_business += $user->product_amount;
                }
                $userActivity->team_count += 1;
                $userActivity->team_business += $user->product_amount;
                $userActivity->total_count += 1;
                $userActivity->total_business += $user->product_amount;
                $userActivity->joining_benefit += $commission;
                $userActivity->self_earning += $commission;
                $userActivity->team_earning += $teamEarning;
                $userActivity->total_earning += ($teamEarning + $commission);
                $userActivity->self_balance += $commission;
                $userActivity->team_balance += $teamEarning;
                $userActivity->total_balance += ($teamEarning + $commission);
            }
            $userActivity->last_payment_date = now()->toDateTimeString();
            //  $userActivity->save();

            //
            $teamEarning += $commission;
            // $this->selectedParentList[] = ["id" => $currentParent->id];
            if (!in_array($currentParent->user_type, [UserTypeEnum::ADMIN, UserTypeEnum::MANAGER])) {
                //$this->selectedParentList[] = ["id" => $currentParent->id];
                $this->addParent($currentParent);
            }
            // Move to the next parent
            $currentParent = $currentParent->parent;
            $level++;
        }
        // dd( $this->selectedParentList);

    }
    private function poolIncome()
    {
        $poolIncomeRates = [
            1 => ["level" => 1, "min" => 3, "income" => 45], // Level 1
            2 => ["level" => 2, "min" => 9, "income" => 60],
            3 => ["level" => 3, "min" => 27, "income" => 125],
            4 => ["level" => 4, "min" => 81, "income" => 250],
            5 => ["level" => 5, "min" => 243, "income" => 500],
            6 => ["level" => 6, "min" => 729, "income" => 1200],
            7 => ["level" => 7, "min" => 2187, "income" => 2500],
        ];
        // Get the list of selected parent IDs
        $userIds = $this->getSelectedParentList();
        // dd($userIds);

        foreach ($userIds as $user) {
            $userId = $user['id'];
            $level = 1;
            $currentPoolIncomeRates = [];
            // Fetch active child counts grouped by level for the user
            $userActiveChildCount = JoiningBenefit::where('parent_id', '=', $userId)
                ->selectRaw('level, COUNT(*) as count')
                ->groupBy('level')
                ->pluck('count', 'level');
            //dd($userActiveChildCount);

            // Check each level against pool income criteria
            foreach ($poolIncomeRates as $rate) {
                if (
                    isset($userActiveChildCount[$rate['level']]) &&
                    $userActiveChildCount[$rate['level']] >= $rate['min']
                ) {
                    $currentPoolIncomeRates[] = $rate;

                    // Save the pool income in the database
                    $poolIncome = PoolIncome::where('user_id', $userId)->where('level', $level)->first();
                    if (!$poolIncome) {

                        PoolIncome::create([
                            'user_id' => $userId,
                            'level' => $rate['level'],
                            'income' => $rate['income'],
                        ]);
                    }
                } else {
                    // If criteria not met for current level, stop further levels
                    break;
                }
            }

            // Optionally, log or process the user's total pool income
            // e.g., update user's total income, etc.
            $totalIncome = array_sum(array_column($currentPoolIncomeRates, 'income'));
            $userActivity = UserActivity::where('user_id', $userId)->firstOrFail();
            $userActivity->pool_income;
            $userActivity->update();
        }

        // Optional: Debug or log the result
        // dd("Pool income distribution completed.");
    }
}
