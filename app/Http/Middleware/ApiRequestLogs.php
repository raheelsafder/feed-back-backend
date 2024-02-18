<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Closure;

class ApiRequestLogs
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uuid = Str::uuid()->toString();
        $logs = new \App\Models\ApiRequestLogs();
        $logs->uuid = $uuid;
        $logs->ip = $request->ip();
        $logs->user_agent = $request->header('user-agent');
        $logs->method = $request->method();
        $logs->path = $request->path();
        $logs->request_data = json_encode(['header' => $request->header(), 'body' => $request->all()]);
        $logs->save();
        $request['request_id'] = $logs->id;
        $request['request_uuid'] = $logs->uuid;
        return $next($request);
    }

    /**
     * @param $request
     * @param $response
     * @return void
     */
    public function terminate($request, $response): void
    {

        if ($response->status() != 200) {
            \App\Models\ApiRequestLogs::find($request['request_id'])->update([
                'header_code' => $response->status(),
                'response_data' => json_encode($response, true)
            ]);
        }
    }
}
