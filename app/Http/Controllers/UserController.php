<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\PasswordChangeRequest;
use App\Http\Requests\User\StatusChangeRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\SuccessResource;
use App\Services\IUserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $response = $this->userService->getAll();

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): SuccessResource|array|null
    {
        $response = $this->userService->store($request);

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $response = $this->userService->getById($id);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $response = $this->userService->update($request, $id);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $response = $this->userService->delete($id);

        return $response;
    }

    public function passwordChange(PasswordChangeRequest $passwordChangeRequest)
    {
        $response = $this->userService->passwordChange($passwordChangeRequest);

        return $response;
    }

    public function statusChange(StatusChangeRequest $statusChangeRequest)
    {
        $response = $this->userService->statusChange($statusChangeRequest);

        return $response;
    }
}
