<?php

namespace App\Services\impl;

use App\Exceptions\GeneralJsonException;
use App\Exceptions\ModelNotFoundException as ExceptionsModelNotFoundException;
use App\Http\Requests\Sale\StoreSaleRequest;
use App\Http\Requests\Sale\UpdateSaleRequest;
use App\Http\Resources\Sale\SaleCollection;
use App\Http\Resources\Sale\SaleResource;
use App\Models\Sale;
use App\Services\ISaleService;
use Exception;

class SaleService implements ISaleService
{
    protected $resourceLoader;

    public function __construct()
    {
        $this->resourceLoader = [
                'product'
        ];
    }
    public function getAll()
    {
        $sales = Sale::with($this->resourceLoader)->get();
        if ($sales->isEmpty()) {
            return response()->json([
                'message' => 'No records found.',
            ], 404); // Return 404 status code
        }

        return SaleCollection::make($sales);

    }

    public function getById( $id)
    {

        // $response=Sale::find($id);
        // if($response){

        //     return SaleResource::make($response);
        // }
        //   dd('sale not found');
        // throw new RecordsNotFoundException();

        try {
            $response = Sale::findOrFail($id);

            return SaleResource::make($response);
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

    public function store(StoreSaleRequest $request)
    {
        $validatedData=$request->validated();

        $pendingSale=Sale::where('user_id',$validatedData['user_id'])->where('is_confirm',false)->first();
        if($pendingSale){
            throw new GeneralJsonException("Order already exists");
        }

        $response = Sale::create($validatedData);

        return SaleResource::make($response);
    }

    public function update(UpdateSaleRequest $request,  $id)
    {
        $response = Sale::find($id)->update($request->validated());

        return SaleResource::make($response);

    }

    public function delete( $id)
    {

        Sale::find($id)->delete();

        return response()->noContent();

    }
}
