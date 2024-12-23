<?php
namespace App\Services;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Contracts\Cache\Store;

interface IProductService{

    function getAll();
    function getById( $id);
    function store(StoreProductRequest $request);
    function update(UpdateProductRequest $request,  $id);
    function delete( $id);
}
