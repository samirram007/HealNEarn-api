<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\SuccessResource;
use App\Models\Product;
use App\Services\IProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index()
    {
        $response=$this->productService->getAll();
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request):SuccessResource|array|null
    {
        $response=$this->productService->store($request);
        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $response=$this->productService->getById($id);
        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //
    }
}