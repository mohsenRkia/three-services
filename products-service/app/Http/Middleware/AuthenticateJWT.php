<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = Http::get('https://users-service:9000/api/validate-token', [
            'headers' => [
                'Authorization' => 'Bearer '.$request->bearerToken()
            ]
        ]);

        dd($response->json());
        $curl = curl_init();

        $token = $request->header('Authorization');
        $token = $request->bearerToken();
        dd($token);
        return $next($request);
    }
}
