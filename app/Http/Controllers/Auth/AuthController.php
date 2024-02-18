<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helper;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param UserRegisterRequest $request
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $registerUser = $this->authService->register($request);
        return Helper::response($request, $registerUser['body'], $registerUser['header_code'], $request['name'] . ' added successfully', true);
    }

    /**
     * @param UserLoginRequest $request
     * @return array|JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse|array
    {
        try {
            $registerUser = $this->authService->login($request);
            return Helper::response($request, $registerUser['body'], $registerUser['header_code'], $request['email'] . ' logged in successfully', true);
        } catch (\Exception $e) {
            return Helper::errorResponse($request, __FUNCTION__, $e);
        }

    }

    public function logout(Request $request): JsonResponse|array
    {
        try {
            $user = auth()->user();
            $registerUser = $this->authService->logout($request);
            return Helper::response($request, $registerUser['body'], $registerUser['header_code'], $user['name'] . ' logged out successfully', true);
        } catch (\Exception $e) {
            return Helper::errorResponse($request, __FUNCTION__, $e);
        }

    }

}
