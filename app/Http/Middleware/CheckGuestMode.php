<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class CheckGuestMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $user = $request->user('api');
        $guest_allowed = Setting::where('option', 'guest_allowed')->first();
        
        if(!$user && (!isset($guest_allowed->value) || $guest_allowed->value == 'off')) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Guests not allowed',    
            ])->setStatusCode(401);
        }

        return $next($request);
    }
}
