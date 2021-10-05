<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;

class QueryResponseException extends QueryException
{
    public function report()
    {
    }

    public function render($request)
    {
        if (config('app.debug')) {
            $response['errors'] = [
                'code' => $this->getCode(),
                'message' => $this->getMessage(),
                'sql' => $this->getSql(),
                'bindings' => $this->getBindings(),
                'line' => $this->getLine(),
                'file' => $this->getFile(),
            ];
        } else {
            $response['error'] = [
                'message' => __('general.error')
            ];
        }

        return response()->json($response, 500);
    }
}
