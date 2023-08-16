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
