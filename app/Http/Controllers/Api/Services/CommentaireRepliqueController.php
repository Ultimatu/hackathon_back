<?php

namespace App\Http\Controllers\Api\Services;

use App\Http\Controllers\Controller;
use App\Models\CommentaireReplique;
use Illuminate\Http\Request;

class CommentaireRepliqueController extends Controller
{
    //

    public function addCommentaireReplique(Request $request)
    {
        $request->validate([
            'id_commentaire' => 'required|integer|exists:commentaires,id',
            'id_user' => 'required|integer|exists:users,id',
            'reponse' => 'required|string',
        ]);

        $commentaireReplique = new CommentaireReplique();
        $commentaireReplique->id_commentaire = $request->input('id_commentaire');
        $commentaireReplique->id_user = $request->input('id_user');
        $commentaireReplique->reponse = $request->input('reponse');
        $commentaireReplique->save();

        return response()->json([
            'success' => 'Commentaire ajouté avec succès'
        ], 200);
    }


    public function getAllCommentaireRepliquesByCommentaire(int $id)
    {
        $commentaireRepliques = CommentaireReplique::where('id_commentaire', $id)->get();
        if ($commentaireRepliques->count() > 0){
            return response()->json([
                'success' => 'Commentaires trouvés',
                'data' => $commentaireRepliques
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de commentaires'
            ], 404);
        }

    }


    public function getAllCommentaireRepliques()
    {

        $commentaireRepliques = CommentaireReplique::all();
        if ($commentaireRepliques->count() > 0){
            return response()->json([
                'success' => 'Commentaires trouvés',
                'data' => $commentaireRepliques
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de commentaires'
            ], 404);
        }

    }


    public function getCommentaireReplique(int $id)
    {
        $commentaireReplique = CommentaireReplique::find($id);
        if (!$commentaireReplique) {
            return response()->json([
                'message' => 'Commentaire non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => 'Commentaire trouvé',
            'data' => $commentaireReplique
        ], 200);
    }


    public function deleteCommentaireReplique(int $id)
    {
        $commentaireReplique = CommentaireReplique::find($id);
        if (!$commentaireReplique) {
            return response()->json([
                'message' => 'Commentaire non trouvé'
            ], 404);
        }
        $commentaireReplique->delete();
        return response()->json([
            'success' => 'Commentaire supprimé avec succès'
        ], 200);
    }
}
