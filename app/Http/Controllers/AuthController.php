<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $email = $request->input('email');
        $name = $request->input('name');
        $password = $request->input('password');
        $isSupplier = $request->input('is_supplier') ?: false;

        $data = $this->authService->registerAndLogin($email, $name, $password, $isSupplier);

        return response()->json([
            'success' => true,
            'status' => Response::HTTP_CREATED,
            'message' => 'User Registered Successfully!',
            'data' => $data
        ], Response::HTTP_CREATED)->withCookie(cookie($data['token'], 60 * 24 * 7));
    }

    public function login(LoginRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $data = $this->authService->login($email, $password);

        return response()->json([
            'success' => true,
            'status' => Response::HTTP_OK,
            'message' => 'User LoggedIn Successfully!',
            'data' => $data
        ], Response::HTTP_OK)->withCookie(cookie('token', $data['token'], 60 * 24 * 7));
    }

    public function logout(Request $request)
    {
        // Get the currently authenticated user
        $user = $request->user();
        Log::info($user);
        $this->authService->logOut($user);
        return redirect()->route('login')->withCookie(cookie('token', "", -1));
    }
}
