<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSignatureValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasHeader("X-Callback-Signature")) {
            abort(401,"Signature Required");
        }

        $json = file_get_contents('php://input');
        $signature = hash_hmac('sha256', $json, env("PRIVATE_KEY"));
        $headerSignature = $request->header("X-Callback-Signature");
        
        if (!hash_equals($signature, $headerSignature)) {
            return abort(400,"Signature Is Invalid");
        }

        return $next($request);
    }
}
