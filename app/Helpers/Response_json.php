<?php 
if (! function_exists('response_json')) {
    /**
     * API Response Helper
     * 
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    function response_json(bool $success, string $message = '', $data = null, int $status = 200)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $status);
    }
}