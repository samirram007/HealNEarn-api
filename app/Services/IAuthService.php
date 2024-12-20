<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

interface IAuthService
{
    public function login(LoginRequest $request);

    public function logout();

    public function register(RegisterRequest $request);

    public function refresh();

    public function profile();
}
