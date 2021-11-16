<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // TODO: Create auth logic

    public function register(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $register = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        if ($register) {
            return response()->json([
                'success' => true,
                'message' => 'Register Success!',
                'data' => ([
                    'token' => 'token'
                ])
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Register Failed!',
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if ($user) {
            if (Hash::check($password, $user->password)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully Login!',
                    'data' => [
                        'token' => 'token',
                    ]
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Password doesnt match!',
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found!',
            ], 404);
        }
    }
}
