<?php

namespace App\Services\impl;

use App\Exceptions\ModelNotFoundException as ExceptionsModelNotFoundException;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Http\Resources\Payment\PaymentCollection;
use App\Http\Resources\Payment\PaymentResource;
use App\Models\Payment;
use App\Services\IPaymentService;
use Exception;

class PaymentService implements IPaymentService
{
    public function getAll()
    {

        return PaymentCollection::make(Payment::all());
    }

    public function getById( $id)
    {

        // $response=Payment::find($id);
        // if($response){

        //     return PaymentResource::make($response);
        // }
        //   dd('payment not found');
        // throw new RecordsNotFoundException();

        try {
            $response = Payment::findOrFail($id);

            return PaymentResource::make($response);
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

    public function store(StorePaymentRequest $request)
    {
        $response = Payment::create($request->validated());

        return PaymentResource::make($response);
    }

    public function update(UpdatePaymentRequest $request,  $id)
    {
        $response = Payment::find($id)->update($request->validated());

        return PaymentResource::make($response);

    }

    public function delete( $id)
    {

        Payment::find($id)->delete();

        return response()->noContent();

    }
}
