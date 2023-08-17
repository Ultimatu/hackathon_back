<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAllUsers()
    {

        
        if (User::all()->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun utilisateur trouvé',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'list de tout les utilisateurs paginé par 10',
            'data' => User::paginate(10)
        ]);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Utilisateur trouvé',
            'data' => $user
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 400);
        }
        if ($user->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non supprimé',
            ], 500);
        }
    }
}
