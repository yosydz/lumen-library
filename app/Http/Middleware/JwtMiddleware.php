<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
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

        if(!$token){
            return response()->json([
                'error' => 'Token not provided'
            ], 401);
        }

        try{
            $credential = JWT::decode($token, env('JWT_SECRET'),['HS256']);
            // var_dump($credential);die;
        }
        catch(ExpiredException $e){
            return response()->json([
                'error' => 'Provided token is expired'
            ], 400);
        }
        catch(Exception $e){
            return response()->json([
                'error' => 'Error while decoding token'
            ], 400);
        }
        $user = User::find($credential->sub);
        // var_dump($user->role);die;
        // $guard = $user->role;
        // if($user->role === 'admin'){
        //     //todo
        // }elseif ($user->role === 'user'){
        //     //todo
        // }
        $request->auth = $user;
        return $next($request);
    }
}
