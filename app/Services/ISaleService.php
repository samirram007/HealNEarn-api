<?php
namespace App\Services;

use App\Http\Requests\Sale\StoreSaleRequest;
use App\Http\Requests\Sale\UpdateSaleRequest;
use App\Models\Sale;
use Illuminate\Contracts\Cache\Store;

interface ISaleService{

    function getAll();
    function getById( $id);
    function store(StoreSaleRequest $request);
    function update(UpdateSaleRequest $request,  $id);
    function delete( $id);
}
