<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        try {
            $credentials = request(['username', 'password']);

            // Validar si el usuario exixte
            $user = User::where('username', request(['username']))->first();
            if ($user) {
                //Comparar las contraseñas para generar el token 
                if (Hash::check($credentials['password'], $user->password)) {

                    $token = auth()->attempt($credentials);
                    $token_f = $this->respondWithToken($token);
                    $response = [
                        "meta" => [
                            "success" => true,
                            "errors" => []
                        ],
                        "data" => [
                            "token" => $token_f->original['access_token'],
                            "minutes_to_expire" => $token_f->original['expires_in'],
                        ]
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        "meta" => [
                            "success" => false,
                            "errors" => ["Password incorrect for: " . $credentials['username']]
                        ]
                    ];
                    return response()->json($response, 401);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
