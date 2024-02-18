<?php

namespace App;

use App\Models\ApiRequestLogs;
use App\Models\ErrorLogs;
use Illuminate\Http\JsonResponse;

class Helper
{
    /**
     * @param $request
     * @param $response
     * @param int $headerCode
     * @param string $activity
     * @param bool $showActivity
     * @return JsonResponse
     */
    public static function response($request, $response, int $headerCode, string $activity = null, bool $showActivity = false): JsonResponse
    {
        $statusCodeWithThereStatus = [
            100 => 'CONTINUE',
            101 => 'SWITCHING_PROTOCOLS',
            200 => 'SUCCESS',
            201 => 'CREATED',
            202 => 'ACCEPTED',
            204 => 'NO_CONTENT',
            300 => 'MULTIPLE_CHOICES',
            301 => 'MOVED_PERMANENTLY',
            302 => 'FOUND',
            304 => 'NOT_MODIFIED',
            400 => 'BAD_REQUEST',
            401 => 'UNAUTHORIZED',
            403 => 'FORBIDDEN',
            404 => 'NOT_FOUND',
            429 => 'TOO_MANY_REQUESTS',
            500 => 'INTERNAL_SERVER_ERROR',
            501 => 'NOT_IMPLEMENTED',
            503 => 'SERVICE_UNAVAILABLE',
            // Add more status codes and messages as needed
        ];
        $lastResponse = [
            'uuid' => $request['request_uuid'],
            'message' => $statusCodeWithThereStatus[$headerCode],
        ];
        if ($headerCode == 200) {
            $lastResponse['body'] = is_array($response) ? $response : [$response];
        } else {
            $lastResponse['errors'] = is_array($response) ? $response : [$response];

        }
        if ($showActivity && in_array($headerCode, [200, 201])) {
            ApiRequestLogs::find($request['request_id'])->update(['activity' => $activity, 'show_activity' => $showActivity]);
        }
        return response()->json($lastResponse, $headerCode);
    }

    /**
     * @param $request
     * @param $functionName
     * @param $e
     * @return array
     */
    public static function errorResponse($request, $functionName, $e): array
    {
//        ErrorLogs::exceptionLogs($request, $functionName, $e);
        return [
            'uuid' => $request['request_uuid'],
            'header_code' => 500,
            'message' => in_array(env('APP_ENV'), ['local', 'development']) ? $e->getFile() . ' function: ' . $functionName : 'INTERNAL_SERVER_ERROR',
            'body' => [
                in_array(env('APP_ENV'), ['local', 'development']) ? $e->getMessage() . ' on line number ' . $e->getLine() : 'Kindly report the issue by using uuid'
            ]
        ];
    }
}
