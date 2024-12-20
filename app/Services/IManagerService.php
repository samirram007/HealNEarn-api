<?php

namespace App\Services;

use App\Http\Requests\Manager\StoreManagerRequest;
use App\Http\Requests\Manager\UpdateManagerRequest;

interface IManagerService
{
    public function getAll();

    public function getById(int $id);

    public function store(StoreManagerRequest $request);

    public function update(UpdateManagerRequest $request, int $id);

    public function delete(int $id);

    public function profile();
}
