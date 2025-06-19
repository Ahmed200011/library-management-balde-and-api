<?php
namespace App\Helpers;

class ApiResponse
{
    static function sendResponse($code, $message, $data)
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($response, $code);
    }
}
