<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['login', 'register']);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Authentication"},
     *     summary="User login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful login, returns user and token"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Connexion refusée, veuillez vérifier vos informations'], 401);
        }
        //revoquer les anciens tokens
        auth()->user()->tokens()->delete();

        $token = auth()->user()->createToken('ApiToken')->plainTextToken;
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'message' => 'Connexion effectuée avec succès',
            'Authorization' => [
                'token' => $token,
                'type' => 'Bearer'
            ]
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Authentication"},
     *     summary="User registration",
     *     @OA\RequestBody(
     *         required=true,
     *            @OA\JsonContent(ref="#/components/schemas/User"),
     *
     *     ),
     *     @OA\Response(response="201", description="Successful registration"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     * )
     * @param UserRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */

    public function register(UserRequest $request)
    {


        //verifier si l'utilisateur existe par mail ou phone
        $user = User::where('email', $request->email)->orWhere('phone', $request->phone)->first();

        if ($user) {
            return response()->json([
                'message' => "Cet utilisateur existe déjà",

            ], 400);
        }
        $request = $request->validated();

        $request['password'] = bcrypt($request['password']);

        $user = User::create($request);


        $tokens = $user->createToken('ApiToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'Authorization' => [
                'token' => $tokens,
                'type' => 'Bearer'
            ],
            'message' => "Enregistré effectuer avec succès"
        ], 201);
    }


    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Authentication"},
     *     summary="User logout",
     *    security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="Successfully logged out"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */
    public function logout(): JsonResponse
    {
        if (Auth::check()) {
            auth()->user()->tokens()->delete();
            return response()->json([
                'message' => 'Successfully logged out',
            ]);
        }
        return response()->json([
            'message' => 'User not logged in',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     tags={"Authentication"},
     *     summary="Refresh authentication token",
     *     @OA\Response(response="200", description="Token refreshed"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */

    public function refresh(): JsonResponse
    {
        return response()->json([
            'user' => Auth::user(),
            'Authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'Bearer ',
            ]
        ]);
    }
}
