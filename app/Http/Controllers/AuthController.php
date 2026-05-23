<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function authWeb(Request $request, AuthService $authService)
    {
        try {
            $result = $authService->authWeb(
                $request->only([
                    'email',
                    'password',
                ])
            );

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'data' => $result,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    public function authCheck(Request $request, AuthService $authService)
    {
        try {
            $result = $authService->authCheck(
                $request->only([
                    'email',
                    'password',
                ])
            );

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'data' => $result,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'status' => true,
                'message' => 'Logout berhasil',
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Logout gagal',
            ], 500);
        }
    }

    public function validate()
    {
        $user = auth()->guard('api')->user();

        return response()->json([
            'status' => true,
            'message' => 'Token valid',
            'user' => $user,
        ]);
    }

    public function redirectGoogle()
    {
        $provider = Socialite::driver('google');

        /** @var GoogleProvider $provider */

        return $provider->stateless()->redirect();

    }

    public function callbackGoogle(AuthService $authService)
    {
        try {
            $provider = Socialite::driver('google');
            /** @var GoogleProvider $provider */
            $googleUser = $provider->stateless()->user();
            $result = $authService->authByEmail(
                $googleUser->getEmail()
            );

            return view('login', ['dataCallbackGoogle' => $result]);
        } catch (Exception $e) {
            return redirect('/login');
        }
    }
}
