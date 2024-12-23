<?php
namespace App\Services;

use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;

interface IPaymentService{

    function getAll();
    function getById( $id);
    function store(StorePaymentRequest $request);
    function update(UpdatePaymentRequest $request,  $id);
    function delete( $id);
}
