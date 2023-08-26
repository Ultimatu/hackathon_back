<?php

namespace App\Http\Controllers\Api\Services;

use App\Http\Controllers\Controller;
use App\Models\Sondage;
use App\Models\TypeSondage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SondageController extends Controller
{


    public function addSondage(Request $request)
    {
        $request->validate([
            'titre' => 'required|string',
            'description'  => 'required|string',
            'date_debut' => 'nullable|datetime',
            'date_fin' => 'nullable|datetime',
            'url_media' => 'nullable',
            'id_type_sondage' => 'integer|exists:types_sondages,id|nullable',
            'commune' => 'required|string'
        ]);

        $sondage = new Sondage();
        $sondage->titre = $request->input('titre');
        $sondage->description = $request->input('description');
        $sondage->date_debut = $request->input('date_debut');
        $sondage->date_fin = $request->input('date_fin');
        $sondage->id_type_sondage = $request->input('id_type_sondage');
        $sondage->commune = $request->input('commune');

        if ($request->hasFile('url_media')) {
            $file = $request->file('url_media');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $file->move('storage/sondages', $fileNameToStore);
            $nameToFront = 'storage/sondages/' . $fileNameToStore;
            $sondage->url_media = $nameToFront;
        }
        else{
            $sondage->url_media = 'sondages/default.jpg';
        }

        $sondage->save();

        return response()->json([
            'success' => 'Sondage ajouté avec succès'
        ], 200);
    }



    public function updateSondage(int $id, Request $request){
        $request->validate([
            'titre' => 'required|string',
            'description'  => 'required|string',
            'date_debut' => 'nullable|datetime',
            'date_fin' => 'nullable|datetime',
            'url_media' => 'nullable',
            'id_type_sondage' => 'integer|exists:types_sondages,id|nullable',
            'commune' => 'required|string'
        ]);

        $sondage = Sondage::find($id);
        if (!$sondage){
            return response()->json([
                'message'=>'Sondage non trouvé',
            ], 400);
        }
        $sondage->titre = $request->input('titre');
        $sondage->description = $request->input('description');
        $sondage->date_debut = $request->input('date_debut');
        $sondage->date_fin = $request->input('date_fin');
        $sondage->id_type_sondage = $request->input('id_type_sondage');
        $sondage->commune = $request->input('commune');

        if ($request->hasFile('url_media')) {
            $file = $request->file('url_media');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $file->move('storage/sondages', $fileNameToStore);
            $nameToFront = 'storage/sondages/' . $fileNameToStore;
           if ($sondage->url_media != 'sondages/default.jpg' && file_exists($sondage->url_media)){
               unlink($sondage->url_media);
           }
            $sondage->url_media = $nameToFront;
        }

        $sondage->save();

        return response()->json([
            'success' => 'Sondage modifié avec succès'
        ], 200);
    }




    public function getAllSondagesForUser()
    {

        $sondages = Sondage::all();

        if (auth()->user()) {
            $userID = auth()->user()->id;

            $sondagesNonVotes = Sondage::whereDoesntHave('resultatsSondages', function ($query) use ($userID) {
                $query->where('id_user', $userID);
            })->get();

            if ($sondagesNonVotes->count() > 0){
                return response()->json([
                    'data' => $sondagesNonVotes
                ], 200);
            }

            else{
                return response()->json([
                    'message' => 'Pas de sondages'
                ], 404);
            }
        }

        if ($sondages->count() > 0){

            return response()->json([
                'data' => $sondages
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de sondages'
            ], 404);
        }

    }

    public function getAllSondages()
    {

        $sondages = Sondage::all();

        //si l'utilisateur est connecté , on affiche les sondages non votés

        if ($sondages->count() > 0){

            return response()->json([
                'data' => $sondages
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de sondages'
            ], 404);
        }

    }


    public function getAllSondageByCommune(string $commune)
    {
        //recuperer
        $sondages = Sondage::where('commune', $commune)->get();
        if ($sondages->count() > 0){
            return response()->json([
                'data' => $sondages
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de sondages'
            ], 404);
        }

    }



    public function getSondage(int $id)
    {
        $sondage = Sondage::find($id);
        if (!$sondage) {
            return response()->json([
                'message' => 'Sondage non trouvé'
            ], 404);
        }

        return response()->json([
            'data' => $sondage
        ], 200);
    }


    public function deleteSondage(int $id){
        $sondage = Sondage::find($id);
        if (!$sondage) {
            return response()->json([
                'message' => 'Sondage non trouvé'
            ], 404);
        }
        $sondage->delete();
        return response()->json([
            'success' => 'Sondage supprimée avec succès'
        ], 200);
    }


    //getByTypes sondages id
    public function getByTypesSondages(int $id)
    {
        $sondages = Sondage::where('id_type_sondage', $id)->get();
        //evier attribut count() sur null
        if ($sondages->count() > 0){
            return response()->json([
                'data' => $sondages
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de sondages'
            ], 404);
        }

    }


    //getByTypes sondage nom

    public function getByTypesSondagesNom(string $nom)
    {
       //recuperer par nom type sondage de la table type_sondages
        $typeSondage = TypeSondage::where('nom', $nom)->first();
        if (!$typeSondage) {
            return response()->json([
                'message' => 'Type de sondage non trouvé'
            ], 404);
        }

        $sondages = Sondage::where('id_type_sondage', $typeSondage->id)->get();
        if ($sondages->count() > 0){
            return response()->json([
                'data' => $sondages
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de sondages'
            ], 404);
        }

    }



    public function getSondagesNotVotedByUser()
    {
        $userID = auth()->user()->id;

        $sondagesNonVotes = Sondage::whereDoesntHave('resultatsSondages', function ($query) use ($userID) {
            $query->where('id_user', $userID);
        })->get();

        if ($sondagesNonVotes->count() > 0){
            return response()->json([
                'data' => $sondagesNonVotes
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de sondages'
            ], 404);
        }


    }


}
