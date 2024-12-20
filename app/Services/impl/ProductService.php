<?php

namespace App\Services\impl;

use App\Exceptions\ModelNotFoundException as ExceptionsModelNotFoundException;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Services\IProductService;
use Exception;

class ProductService implements IProductService
{
    public function getAll()
    {

        return ProductCollection::make(Product::all());
    }

    public function getById(int $id)
    {

        // $response=Product::find($id);
        // if($response){

        //     return ProductResource::make($response);
        // }
        //   dd('product not found');
        // throw new RecordsNotFoundException();

        try {
            $response = Product::findOrFail($id);

            return ProductResource::make($response);
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

    public function store(StoreProductRequest $request)
    {
        $response = Product::create($request->validated());

        return ProductResource::make($response);
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $response = Product::find($id)->update($request->validated());

        return ProductResource::make($response);

    }

    public function delete(int $id)
    {

        Product::find($id)->delete();

        return response()->noContent();

    }
}
