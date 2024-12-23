<?php

namespace App\Services\impl;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\User\UserResource;
use App\Models\Sale;
use App\Models\User;
use App\Models\UserActivity;
use App\Services\IAuthService;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService implements IAuthService
{
    protected $resourceLoader;

    public function __construct()
    {
        $this->resourceLoader = [
            //buildings order by id desc
            'parent',
            'manager',
        ];
    }

    public function login(LoginRequest $request)
    {

        $credentials = $request->validated();
        $token = Auth::attempt($credentials);
        $user = Auth::guard('api')->user();

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid login credentials',
            ], 401);
        }
        $refreshToken = JWTAuth::claims([
            'exp' => now()->addDays(30)->timestamp,  // Set expiration for 30 days
            'sub' => $user->id,                    // Optional: use user's email as 'sub'
        ])->fromUser($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful!',
            'user' => new UserResource($user),
            'data' => [
                'token' => $token,
                'refreshToken' => $refreshToken,
                'type' => 'bearer',
            ],
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([

        ]);
    }

    public function register(RegisterRequest $request)
    {

        $user = User::create($request->validated());
        // Extract validated data
        $validatedData = $request->validated();

        // Extract sale data
        $saleData = $validatedData['sale'] ?? null;

        // Remove sale from member data to create the user
        unset($validatedData['sale']);

        // Create the member
        $user = User::create($validatedData);

        // If sale data exists, associate it with the created member
        if ($saleData) {
            $saleData['user_id'] = $user->id; // Add user_id to sale
            $sale = Sale::create($saleData); // Assuming you have a `Sale` model
        }

        $userActivityExists = UserActivity::where('user_id', $user->id)->exists();

        if (!$userActivityExists) {
            $userActivity = new UserActivity();
            $userActivity->user_id = $user->id;
            $userActivity->save();
        }

        $token = Auth::login($user);


        $refreshToken = JWTAuth::claims([
            'exp' => now()->addDays(30)->timestamp,  // Set expiration for 30 days
            'sub' => $user->id,                    // Optional: use user's email as 'sub'
        ])->fromUser($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => MemberResource::make($user),
            'data' => [
                'token' => $token,
                'refreshToken' => $refreshToken,
                'type' => 'bearer',

            ],
        ]);
    }

    public function refresh()
    {
        // dd(Auth::user());
        $user = Auth::user();
        $token = Auth::refresh();
        $refreshToken = JWTAuth::claims([
            'exp' => now()->addDays(30)->timestamp,  // Set expiration for 30 days
            'sub' => $user->id,                    // Optional: use user's email as 'sub'
        ])->fromUser($user);

        return response()->json([
            'status' => 'success',
            'user' => new UserResource($user),
            'data' => [
                'token' => $token,
                'refreshToken' => $refreshToken,
                'type' => 'bearer',
            ],
        ]);
    }

    public function profile()
    {
        $response = auth()->user();

        return UserResource::make($response->load($this->resourceLoader));
    }
}
