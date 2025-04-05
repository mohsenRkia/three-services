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
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'user-service/api/validate-token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));


        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;
        if($response === false) {
            dd('CURL Error: '.curl_error($curl));
        }
        dd($response);







        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $response = Http::withHeaders(['Authorization' => $token])->withOptions(["verify"=>false])
            ->get('http://user-service/api/validate-token');

        if ($response->failed()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
