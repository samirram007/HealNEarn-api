<?php

namespace App\Services;

use App\Http\Requests\User\PasswordChangeRequest;
use App\Http\Requests\User\StatusChangeRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

interface IUserService
{
    public function getAll();

    public function getById(int $id);

    public function store(StoreUserRequest $request);

    public function update(UpdateUserRequest $request, int $id);

    public function delete(int $id);

    public function profile();

    public function passwordChange(PasswordChangeRequest $passwordChangeRequest);

    public function statusChange(StatusChangeRequest $statusChangeRequest);
}
