<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get api token
        if(!empty($request->bearerToken())) {
            $api_token = $request->bearerToken();
        } else {
            $api_token = $request->api_token;
        }

        // Check if api token is available
        if(empty($api_token)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Invalid user token',    
            ])->setStatusCode(401);
        }

        // Find user according to the api token
        $user = User::withTrashed()
            ->where('api_token', $api_token)
            ->first();

        // If user not found
        if(!$user) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'User not found',    
            ])->setStatusCode(401);
        }

        // If user found, but deleted
        if(!empty($user->deleted_at)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Account has been deleted. Please contact to the admin for more info.',    
            ])->setStatusCode(401);
        }

        // If user found
        return $next($request);
    }
}
