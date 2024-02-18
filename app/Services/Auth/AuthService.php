<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Helper;
use Exception;

class AuthService
{
    public static function register($request): JsonResponse|array
    {
        try {
            DB::beginTransaction();
            User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
            DB::commit();
            return [
                'header_code' => 201,
                'body' => 'User created successfully',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return Helper::errorResponse($request, __FUNCTION__, $e);
        }
    }

    /**
     * @param $request
     * @return JsonResponse|array
     */
    public static function login($request): JsonResponse|array
    {
        try {
            $credentials = $request->only('email', 'password');
            $validCred = auth('web')->attempt($credentials);
            if ($validCred) {
                $user = auth('web')->user();
                $accessToken = $user->createToken('FeedBack')->accessToken;
                return [
                    'header_code' => 200,
                    'body' => [
                        'token' => $accessToken,
                        'user' => [
                            'name' => $user['name'],
                            'email' => $user['email'],
                            'last_login' => $user['last_login'],

                        ],
                    ],
                ];
            } else {
                return [
                    'header_code' => 400,
                    'body' => 'Invalid credentials',
                ];
            }
        } catch (Exception $e) {
            return Helper::errorResponse($request, __FUNCTION__, $e);
        }
    }

    public static function logout($request): JsonResponse|array
    {
        try {
            auth()->user()->token()->revoke();;
            return [
                'header_code' => 200,
                'body' => 'logged out successfully'
            ];
        } catch (Exception $e) {
            return Helper::errorResponse($request, __FUNCTION__, $e);
        }
    }
}
