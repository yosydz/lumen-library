<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'error' => 'Token required'
            ], 401);
        }

        //TODO: handler jika token tidak sama , signature verification

        try {
            $credential = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Provided token is expired'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error while decoding token'
            ], 400);
        }
        $user = User::find($credential->sub);
        if($user->role != $guard){
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 400);
        }
        $request->auth = $user;
        return $next($request);
    }
}
