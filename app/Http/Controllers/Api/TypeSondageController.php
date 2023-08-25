<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TypeSondage;
use Illuminate\Http\Request;

/**
 * @group TypeSondage management
 */
class TypeSondageController extends Controller
{


    public function addTypeSondage(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'description' => 'string',
        ]);

        $typeSondage = new TypeSondage();

        if (TypeSondage::where('nom', $request->input('nom'))->first()) {
            return response()->json([
                'message' => 'Ce type de sondage existe déjà'
            ], 409);
        }


        $typeSondage->nom = $request->input('nom');
        $typeSondage->description = $request->input('description');

        $typeSondage->save();

        return response()->json([
            'success' => 'Type de sondage ajouté avec succès'
        ], 200);
    }


    public function updateTypeSondage(Request $request, int $id)
    {
        $request->validate([
            'nom' => 'required|string',
            'description' => 'string',
        ]);

        $typeSondage = TypeSondage::find($id);

        if (!$typeSondage) {
            return response()->json([
                'message' => 'Type de sondage non trouvé'
            ], 404);
        }

        if (TypeSondage::where('nom', $request->input('nom'))->first()) {
            return response()->json([
                'message' => 'Ce type de sondage existe déjà'
            ], 409);
        }

        $typeSondage->nom = $request->input('nom');
        $typeSondage->description = $request->input('description');

        $typeSondage->save();

        return response()->json([
            'success' => 'Type de sondage modifié avec succès'
        ], 200);
    }




    public function getAllTypeSondages()
    {

        $typeSondages = TypeSondage::all();
        //si c'est vide et eviter attribut count() sur null

        if ($typeSondages->isEmpty()) {
            return response()->json([
                'message' => 'Pas de types de sondages'
            ], 404);
        }

        $paginatedTypeSondages = TypeSondage::all();
        return response()->json([
            'data' => $paginatedTypeSondages
        ], 200);

    }



    public function getTypeSondage(int $id)
    {
        $typeSondage = TypeSondage::find($id);
        if (!$typeSondage) {
            return response()->json([
                'message' => 'Type de sondage non trouvé'
            ], 404);
        }

        return response()->json([
            'data' => $typeSondage
        ], 200);
    }


    public function deleteTypeSondage(int $id)
    {
        $typeSondage = TypeSondage::find($id);
        if (!$typeSondage) {
            return response()->json([
                'message' => 'Type de sondage non trouvé'
            ], 404);
        }

        $typeSondage->delete();

        return response()->json([
            'success' => 'Type de sondage supprimé avec succès'
        ], 200);
    }


}
