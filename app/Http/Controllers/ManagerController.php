<?php

namespace App\Http\Controllers;

use App\Http\Requests\Manager\StoreManagerRequest;
use App\Http\Requests\Manager\UpdateManagerRequest;
use App\Http\Resources\SuccessResource;
use App\Services\IManagerService;

class ManagerController extends Controller
{
    protected $managerService;

    public function __construct(IManagerService $managerService)
    {
        $this->managerService = $managerService;
    }

    public function index()
    {
        $response = $this->managerService->getAll();

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreManagerRequest $request): SuccessResource|array|null
    {
        $response = $this->managerService->store($request);

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $response = $this->managerService->getById($id);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateManagerRequest $request, int $id)
    {
        $response = $this->managerService->update($request, $id);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $response = $this->managerService->delete($id);

        return $response;
    }
}
