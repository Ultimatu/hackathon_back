<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meet_Participant;
use Illuminate\Http\Request;

class MeetParticipantController extends Controller
{

    public function joinMeet(Request $request){
        $request->validate([
            'id_meet'=>'required|integer|exists:meets,id',
            'id_user'=>'required|integer|exists:users,id',
        ]);

        //verifier si le candidat est deja participant
        $meetParticipant = Meet_Participant::where('id_meet',$request->id_meet)
            ->where('id_user',$request->id_user)
            ->first();
        if ($meetParticipant) {
            return response()->json([
                'message'=>'Vous participez déjà au meet',
                'data'=>$meetParticipant,
            ],409);
        }

        $meetParticipant = Meet_Participant::create([
            'id_meet'=>$request->id_meet,
            'id_user'=>$request->id_user,
        ]);

        return response()->json([
            'success'=>'Vous avez join le meet',
            'data'=>$meetParticipant,
        ],201);
    }


    public function quitMeet(Request $request){
        $request->validate([
            'id_meet'=>'required|integer|exists:meets,id',
            'id_user'=>'required|integer|exists:users,id',
        ]);

        $meetParticipant = Meet_Participant::where('id_meet',$request->id_meet)
            ->where('id_user',$request->id_user)
            ->first();

        if (!$meetParticipant) {
            return response()->json([
                'message'=>'Vous ne participez pas au meet',
            ],409);
        }

        $meetParticipant->delete();

        return response()->json([
            'success'=>'Vous avez quitté le meet',
        ],200);
    }


    public function getParticipants(Request $request){
        $request->validate([
            'id_meet'=>'required|integer|exists:meets,id',
        ]);

        $meetParticipant = Meet_Participant::where('id_meet',$request->id_meet)
            ->with('users')
            ->get();

        return response()->json([
            'success'=>'Liste des participants',
            'data'=>$meetParticipant,
        ],200);
    }


    public function getMeets(int $id_user){


        $meetParticipant = Meet_Participant::where('id_user',$id_user)
            ->with('meets')
            ->get();

        return response()->json([
            'success'=>'Liste des meet',
            'data'=>$meetParticipant,
        ],200);

    }


}
