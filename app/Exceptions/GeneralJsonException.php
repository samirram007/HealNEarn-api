<?php

namespace App\Exceptions;

use Exception;

class GeneralJsonException extends Exception
{
    protected $code=422;
    //
    /**
     * Report the exception
     *
     * @return void
     */
    public function report(){

    }

    /**
     * Render the exception as an HTTP response
     *
     * @param \Illuminate\Http\Request $request
     */
    public function render($request){
        return response()->json([
            'status'=> false,
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ], $this->getCode());

    }

}
