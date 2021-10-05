<?php

namespace App\Exceptions;

use Exception;

class ReturnOtherException extends Exception
{
    public function report()
    {
    }

    public function render($request)
    {
        if (config('app.debug')) {
            $response['errors'] = [
                'message' => $this->getMessage(),
                'line' => $this->getLine(),
                'file' => $this->getFile(),
                'code' => $this->getCode()
            ];
        } else {
            $response['error'] = ['message' => __('general.error')];
        }

        return response()->json($response, 500);
    }
}
