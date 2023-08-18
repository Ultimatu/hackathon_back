<?php

namespace App\Http\Controllers\Api\PrivateApi;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

    public function addPhoto(int $id, Request $request)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }


       //recuperer le fichier
        $file = $request->file('photo');
        if (!$file) {
            return response()->json([
                'message' => 'Photo not found'
            ], 404);
        }
        if ($user->photo_url) {
            Storage::delete('public/photos/' . $user->photo_url);
        }
        //recuperer le nom du fichier
        $fileName = $file->getClientOriginalName();
        //recuperer l'extension du fichier
        $extension = $file->getClientOriginalExtension();

        //generer un nom unique pour le fichier
        $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

        //deplacer le fichier vers le dossier de stockage

        $file->move('storage/photos', $fileNameToStore);

        //enregistrer le nom du fichier dans la base de donnees
        $user->photo_url = $fileNameToStore;
        $user->save();


        return response()->json([
            'message' => 'Photo added successfully'
        ], 200);
    }

}
