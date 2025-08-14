<?php

if (!function_exists('success')) {
    function success($data = [], $message = '', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }
}

if (!function_exists('error')) {
    function error($message = '', $errors = [], $status = 401)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
            'status'  => $status,
        ], $status);
    }
}
