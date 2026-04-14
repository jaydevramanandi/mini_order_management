<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $user = $this->userRepository->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            $token = $user->createToken('auth-token')->plainTextToken;

            return (new AuthResource([
                'message' => 'User registered successfully',
                'user' => $user,
                'token' => $token,
            ]))->response()->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login user and return token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $user = $this->userRepository->findByEmail($validated['email']);

            if (!$user || !$this->userRepository->checkPassword($user, $validated['password'])) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $token = $user->createToken('auth-token')->plainTextToken;

            return (new AuthResource([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ]))->response();

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout user and revoke token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Logged out successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Logout failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get authenticated user info.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }
}