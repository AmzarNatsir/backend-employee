<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    // /**
    //  * @OA\Post(
    //  *     path="/api/register",
    //  *     tags={"Auth"},
    //  *     summary="Register new user",
    //  *     @OA\RequestBody(
    //  *         required=true,
    //  *         @OA\JsonContent(
    //  *             required={"name", "email", "password"},
    //  *             @OA\Property(property="name", type="string", example="John Doe"),
    //  *             @OA\Property(property="email", type="string", example="john@example.com"),
    //  *             @OA\Property(property="password", type="string", example="password123")
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=201,
    //  *         description="User registered successfully"
    //  *     ),
    //  *     @OA\Response(
    //  *         response=422,
    //  *         description="Validation error"
    //  *     )
    //  * )
    //  */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'response_code' => 201,
                'status' => 'success',
                'message' => 'User registered successfully',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Registration failed',
            ], 500);
        }
    }

    // /**
    //  * @OA\Post(
    //  *     path="/api/login",
    //  *     tags={"Auth"},
    //  *     summary="Login user",
    //  *     @OA\RequestBody(
    //  *         required=true,
    //  *         @OA\JsonContent(
    //  *             required={"email", "password"},
    //  *             @OA\Property(property="email", type="string", example="amzar@mail.com"),
    //  *             @OA\Property(property="password", type="string", example="password123")
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Login successful"
    //  *     ),
    //  *     @OA\Response(
    //  *         response=401,
    //  *         description="Invalid credentials"
    //  *     )
    //  * )
    //  */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'response_code' => 401,
                    'status' => 'error',
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Login failed',
            ], 500);
        }
    }

    // /**
    //  * @OA\Get(
    //  *     path="/api/get-user",
    //  *     tags={"Auth"},
    //  *     summary="Get authenticated user list",
    //  *     security={{"sanctum":{}}},
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Success",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="response_code", type="integer", example=200),
    //  *             @OA\Property(property="status", type="string", example="success"),
    //  *             @OA\Property(
    //  *                 property="data",
    //  *                 type="object",
    //  *                 @OA\Property(property="current_page", type="integer", example=1),
    //  *                 @OA\Property(
    //  *                     property="data",
    //  *                     type="array",
    //  *                     @OA\Items(
    //  *                         @OA\Property(property="id", type="integer", example=1),
    //  *                         @OA\Property(property="name", type="string", example="John Doe"),
    //  *                         @OA\Property(property="email", type="string", example="john@example.com")
    //  *                     )
    //  *                 )
    //  *             )
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=401,
    //  *         description="Unauthenticated",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="response_code", type="integer", example=401),
    //  *             @OA\Property(property="status", type="string", example="error"),
    //  *             @OA\Property(property="message", type="string", example="Unauthenticated")
    //  *         )
    //  *     )
    //  * )
    //  */

    public function getUser()
    {
        try {
            $user  = User::latest()->paginate(10);

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Failed to retrieve user',
            ], 500);
        }
    }

    // /**
    //  * @OA\Post(
    //  *     path="/api/logout",
    //  *     tags={"Auth"},
    //  *     summary="Logout user",
    //  *     security={{"sanctum":{}}},
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Logged out successfully"
    //  *     ),
    //  *     @OA\Response(
    //  *         response=401,
    //  *         description="Unauthenticated"
    //  *     )
    //  * )
    //  */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'message' => 'Logged out successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Logout failed',
            ], 500);
        }
    }
}
