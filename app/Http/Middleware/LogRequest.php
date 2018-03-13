<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        $method = strtoupper($request->getMethod());
        $uri = $request->getUri();
        $user = $request->user();
        $ip = json_encode($request->ips());
        $bodyAsJson = json_encode($request->all());
        $message = now()." - {$method} {$uri} - Body: {$bodyAsJson} - user: { $user } - Ip: { $ip }";

        if($request->wantsJson()){
            $message .= " - Response: { ".$response->getContent()." }";
        }

        Storage::append('request.txt',$message);
    }
}
