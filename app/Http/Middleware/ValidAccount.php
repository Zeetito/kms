<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Check if user has names 
        if ($request->user() && $request->user()->contacts == null) {
            return response()->json([
                'message' => 'Please update your contact information.',
                'redirect_to' => route('update_contacts')
            ], 403); // You can use a 403 Forbidden status or another appropriate status code
        }

        return $next($request);
    }
}
