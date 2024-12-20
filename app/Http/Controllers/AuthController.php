<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\IAuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(IAuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        //dd($request->only(['username', 'password']));
        $response = $this->authService->login($request);

        return $response;
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->authService->register($request);

        return $response;
    }

    public function logout()
    {
        $response = $this->authService->logout();

        return $response;
    }

    public function profile()
    {

        $response = $this->authService->profile();

        return $response;
    }

    public function refresh()
    {
        $response = $this->authService->refresh();

        return $response;
    }
}
