<?php

namespace App\Http\Controllers\Api\Services;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{


    public function addCommentaire(Request $request)
    {
        $id_user = auth()->user()->id;
        $request['id_user'] = $id_user;
        $request->validate([
            'id_post' => 'required|integer|exists:posts,id',
            'id_user' => 'required|integer|exists:users,id',
            'commentaire' => 'required|string',
        ]);

        $commentaire = new Commentaire();
        $commentaire->id_post = $request->input('id_post');
        $commentaire->id_user = $request->input('id_user');
        $commentaire->commentaire = $request->input('commentaire');
        $commentaire->save();

        return response()->json([
            'message' => 'Commentaire added successfully'
        ], 200);
    }

    public function getAllCommentaires()
    {

        $commentaires = Commentaire::all();
        if ($commentaires->count() > 0){
            return response()->json([
                'data' => $commentaires
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de commentaires'
            ], 404);
        }

    }


    public function getPostCommentaires(int $id)
     {
          $commentaires = Commentaire::where('id_post', $id)->get();
          if ($commentaires->count() > 0){
                return response()->json([
                 'data' => $commentaires
                ], 200);
          }

          else{
                return response()->json([
                 'message' => 'Pas de commentaires'
                ], 200);
          }

    }


    public function getUserCommentaires(int $id)
    {
        $commentaires = Commentaire::where('id_user', $id)->get();
        if ($commentaires->count() > 0){
            return response()->json([
                'data' => $commentaires
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de commentaires'
            ], 404);
        }
    }


    public function deleteCommentaire(int $id)
    {
        $commentaire = Commentaire::find($id);
        if (!$commentaire) {
            return response()->json([
                'message' => 'Commentaire non trouvé'
            ], 404);
        }
        $commentaire->delete();
        return response()->json([
            'message' => 'Commentaire supprimé avec succès'
        ], 200);
    }


    public function deleteCommentaireByUserAndId(int $id, int $id_user)
    {
        $commentaire = Commentaire::where('id', $id)->where('id_user', $id_user)->first();
        if (!$commentaire) {
            return response()->json([
                'message' => 'Commentaire non trouvé'
            ], 404);
        }
        $commentaire->delete();
        return response()->json([
            'message' => 'Commentaire supprimé avec succès'
        ], 200);
    }


    public function updateCommentaire(int $id, Request $request)
    {
        $commentaire = Commentaire::find($id);
        if (!$commentaire) {
            return response()->json([
                'message' => 'Commentaire non trouvé'
            ], 404);
        }

        $request->validate([
            'commentaire' => 'required|string'
        ]);

        $commentaire->commentaire = $request->input('commentaire');

        $commentaire->save();

        return response()->json([
            'message' => 'Commentaire modifié avec succes'
        ], 200);
    }



}
