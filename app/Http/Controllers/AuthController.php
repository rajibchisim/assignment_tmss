<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register', 'user']]);
    }

    public function user()
    {
        if(Auth::check()) {
            return response()->json([
                'status' => 204,
                'user' => Auth::user()
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized',
            ]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized',
            ], 200);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 204,
                'auth' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ]);

    }

    public function register(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]
        );

        if($validator->fails()) {
            return response()->json(
                [
                    'status' => 422,
                    'errors' => $validator->errors()

                ]
            );
        }
        $validated = $validator->validate();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 200,
            'message' => 'Account registration successful',
            'auth' => [
                'token' => $token,
                'user' => $user,
            ]
        ]);
    }

    public function logout()
    {
        // Auth::logout(true);
        Auth::logout(true);
        Auth::invalidate(true);
        // Auth::refresh();
        return response()->json([
            'status' => 200,
            'message' => 'Successfully logged out',
            'user' => Auth::user()
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 200,
            'auth' => [
                'token' => Auth::refresh(),
                'user' => Auth::user(),
            ]
        ]);
    }

}
