<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resultats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultatsVotesController extends Controller
{

    public function calculerResultats(int $id){

        $resultats = DB::select('SELECT candidats.id COUNT(votes.id_candidat) as nbre_voix FROM candidats, votes WHERE votes.id_candidat = candidats.id AND votes.id_election = ? GROUP BY candidats.id ORDER BY nbre_voix DESC', [$id]);



        return response()->json([
            'success'=>'Resultats',
            'data'=>$resultats,
        ],200);
    }

    public function addResultat(Request $request){
        $request->validate([
            'id_election'=>'required|integer|exists:elections,id',
            'id_candidat'=>'required|integer|exists:candidats,id',
            'nb_votes'=>'required|integer',
            'rang'=>'required|integer'
        ]);

        $resultat = Resultats::create([
            'id_election'=>$request->id_election,
            'id_candidat'=>$request->id_candidat,
            'nb_votes'=>$request->nb_votes,
            'rang'=>$request->rang,
        ]);


        return response()->json([
            'success'=>'Ajout avec succes',
            'data'=>$resultat,
        ],201);
    }


    public function getResultats(int $id){

        $resultat = Resultats::where('id_election',$id)
            ->get();

        return response()->json([
            'success'=>'Liste des resultats',
            'data'=>$resultat,
        ],200);

    }


    public function searchResultats(Request $request){
        $request->validate([
            'val'=>'required|string',
        ]);

        $resultat = Resultats::search($request->val)
            ->get();

        if ($resultat->isEmpty()) {
            return response()->json([
                'message'=>'Aucun resultat trouvé',
                'data'=>$resultat,
            ],404);
        }

        return response()->json([
            'success'=>'Resultats',
            'data'=>$resultat,
        ],200);

    }


}
