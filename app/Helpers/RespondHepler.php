<?php

namespace App\Helpers;

class RespondHepler {
    /**
     * Summary of formatJsonResponseData
     * @param int|string $code
     * @param string $message
     * @param int $statusCode
     * @param array $data
     * @param array $errors
     * @param array $headers
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public static function formatJsonResponseData(int|string $code, string $message = '', int $statusCode, array $data = [], array $errors = [], array $headers = []) {
        $responseData = [
            'meta' => [
                'code' => $code,
                'message' => $message,
            ],
        ];

        if (!empty($data)) {
            $responseData['data'] = $data;
        }

        if (!empty($errors)) {
            $responseData['errors'] = $errors;
        }
        
        return response()->json($responseData, $statusCode, $headers);
    }
}