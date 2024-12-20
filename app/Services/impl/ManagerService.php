<?php

namespace App\Services\impl;

use App\Enums\UserTypeEnum;
use App\Exceptions\ModelNotFoundException as ExceptionsModelNotFoundException;
use App\Http\Requests\Manager\StoreManagerRequest;
use App\Http\Requests\Manager\UpdateManagerRequest;
use App\Http\Resources\Manager\ManagerCollection;
use App\Http\Resources\Manager\ManagerResource;
use App\Models\User as Manager;
use App\Services\IManagerService;
use Exception;

class ManagerService implements IManagerService
{
    protected $resourceLoader;

    public function __construct()
    {
        $this->resourceLoader = [
            //buildings order by id desc
            'parent',
            'user_activity'

        ];
    }

    public function getAll()
    {

        $managers = Manager::with($this->resourceLoader)->where('user_type', UserTypeEnum::MANAGER)->get();
        if ($managers->isEmpty()) {
            return response()->json([
                'message' => 'No records found.',
            ], 404); // Return 404 status code
        }

        return ManagerCollection::make($managers);

    }

    public function getById(int $id)
    {

        try {
            $response = Manager::where('user_type', UserTypeEnum::MANAGER)->findOrFail($id);

            return ManagerResource::make($response);
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

    public function store(StoreManagerRequest $request)
    {
        $response = Manager::create($request->validated());

        return ManagerResource::make($response);
    }

    public function update(UpdateManagerRequest $request, int $id)
    {
        $response = Manager::find($id)->update($request->validated());

        return ManagerResource::make($response);
    }

    public function delete(int $id)
    {
        Manager::find($id)->delete();

        return response()->noContent();
    }

    public function profile()
    {
        $response = auth()->user();

        return ManagerResource::make($response);
    }
}
