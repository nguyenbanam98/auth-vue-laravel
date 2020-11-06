<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login()
    {
        try {

            $credentials = request()->only(['email', 'password']);

            $token = auth()->attempt($credentials);

            if (!$token) {
                return response()->json([
                    'errors' => [
                        'status' => false,
                        'code' => 401,
                        'message' => 'Unauthorized',
                    ],
                ], 401);
            }

            return $this->respondWithToken($token);

        } catch (\Throwable $e) {
            return response()->json([
                'errors' => [
                    'status' => false,
                    'code' => 500,
                    'message' => $e->getMessage(),
                ],
            ], 500);
        }
    }

    public function logout()
    {
        try {

            auth()->logout();

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'Logout successfully',
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'errors' => [
                    'status' => false,
                    'code' => 500,
                    'message' => $e->getMessage(),
                ],
            ], 500);
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function register(RegisterRequest $request)
    {
        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'Success' => true,
                'data' => $user
            ], 200);

        } catch (\Throwable $e) {
            
            return response()->json([
                'errors' => [
                    'status' => false,
                    'code' => 500,
                    'message' => $e->getMessage(),
                ],
            ], 500);
        }
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user(),
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
