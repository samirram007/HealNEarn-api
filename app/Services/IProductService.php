<?php
namespace App\Services;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Contracts\Cache\Store;

interface IProductService{

    function getAll();
    function getById(int $id);
    function store(StoreProductRequest $request);
    function update(UpdateProductRequest $request, int $id);
    function delete(int $id);
}
