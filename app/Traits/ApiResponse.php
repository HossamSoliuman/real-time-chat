<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a success response.
     *
     * @param array|string $data
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse($data, $code = 200)
    {
        return response()->json(['data' => $data], $code);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    /**
     * Return a custom response.
     *
     * @param array $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    public function customResponse($data, $message = null, $code = 200)
    {
        $response = ['data' => $data];

        if (!is_null($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }
}