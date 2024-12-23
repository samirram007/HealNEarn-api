<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\StoreSaleRequest;
use App\Http\Resources\SuccessResource;
use App\Models\Sale;
use App\Services\ISaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(ISaleService $saleService)
    {
        $this->saleService = $saleService;
    }
    public function index()
    {
        $response=$this->saleService->getAll();
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request):SuccessResource|array|null
    {
        $response=$this->saleService->store($request);
        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $response=$this->saleService->getById($id);
        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        //
    }
}
