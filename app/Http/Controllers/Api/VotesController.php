<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use Illuminate\Http\Request;

class VotesController extends Controller
{

    public function addVote(Request $request){

        $request->validate([
            'id_election'=>'required|integer|exists:elections,id',
            'id_user'=>'required|integer|exists:users,id',
            'id_candidat'=>'required|integer|exists:candidats,id',
        ]);

        $vote = Vote::where('id_election',$request->id_election)
            ->where('id_user',$request->id_user)
            ->first();

        if ($vote) {
            return response()->json([
                'message'=>'Vous avez déjà voté',
                'data'=>$vote,
            ],409);
        }

        $vote = Vote::create([
            'id_election'=>$request->id_election,
            'id_user'=>$request->id_user,
            'id_candidat'=>$request->id_candidat,
        ]);

        return response()->json([
            'success'=>'Vous avez voté',
            'data'=>$vote,
        ],201);

    }


    public function getVote(Request $request){
        $request->validate([
            'id_election'=>'required|integer|exists:elections,id',
            'id_user'=>'required|integer|exists:users,id',
        ]);

        $vote = Vote::where('id_election',$request->id_election)
            ->where('id_user',$request->id_user)
            ->first();

        if (!$vote) {
            return response()->json([
                'message'=>'Vous n\'avez pas encore voté',
            ],409);
        }

        return response()->json([
            'success'=>'Vous avez déjà voté',
            'data'=>$vote,
        ],200);

    }


    public function getVotes(Request $request){
        $request->validate([
            'id_election'=>'required|integer|exists:elections,id',
        ]);

        $vote = Vote::where('id_election',$request->id_election)
            ->get();

        return response()->json([
            'success'=>'Liste des votes',
            'data'=>$vote,
        ],200);

    }


    public function deleteVote(Request $request){
        $request->validate([
            'id_election'=>'required|integer|exists:elections,id',
            'id_user'=>'required|integer|exists:users,id',
        ]);

        $vote = Vote::where('id_election',$request->id_election)
            ->where('id_user',$request->id_user)
            ->first();

        if (!$vote) {
            return response()->json([
                'message'=>'Vous n\'avez pas encore voté',
            ],409);
        }

        $vote->delete();

        return response()->json([
            'success'=>'Vous avez supprimé votre vote',
        ],200);

    }






}
