<?php

namespace App\Http\Traits;

trait GeneralTraits {
    /**
     * Method for returning successful responses
     * Contains: message, data, code
     */
    public function returnSuccess($msg, $data, $code=200) {
        return response()->json([
            'message' => $msg,
            'data'    => $data,
            'code'    => $code,
        ]);
    }

    /**
     * Method for returning Data responses
     * Contains: message, key, value, code
     */
    public function returnData($msg, $data, $key, $value, $code=200) {
        return response()->json([
            'message' => $msg,
            'data'    => $data,
            $key      => $value,
            'code'    => $code,
        ]);
    }

    /**
     * Method for returning NOT FOUND responses
     * Contains: error message, code
     */
    public function returnNotFound($error, $code=404) {
        return response()->json([
            'error'   => $error,
            'code'    => $code,
        ]);
    }

    /**
     * Method for returning UN-AUTHORIZED responses
     * Contains: error message, code
     */
    public function returnUnauthorized($error='Unauthorized', $code=401) {
        return response()->json([
            'error'   => $error,
            'code'    => $code,
        ]);
    }

    /**
     * Method for returning Exception responses
     * Contains: error message, exception, code
     */
    public function returnException($msg, $exception, $code=403) {
        return response()->json([
            'message'   => $msg,
            'exception' => $exception,
            'code'      => $code,
        ]);
    }
}
