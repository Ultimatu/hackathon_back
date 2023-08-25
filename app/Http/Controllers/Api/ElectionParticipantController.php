<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ElectionParticipant;
use Illuminate\Http\Request;

class ElectionParticipantController extends Controller
{
    public function addPartcicipant(Request $request){
        $request->validate([
            'id_election' => 'required|integer|exists:elections,id',
            'id_candidat' => 'required|integer|exists:candidats,id',
        ]);
        //verifier si le candidat est deja participant
        $electionParticipant = ElectionParticipant::where('id_election', $request->id_election)
            ->where('id_candidat', $request->id_candidat)
            ->first();
        if ($electionParticipant) {
            return response()->json([
                'message' => 'Participant already exists',
                'data' => $electionParticipant,
            ], 409);
        }

        $electionParticipant = ElectionParticipant::create([
            'id_election' => $request->id_election,
            'id_candidat' => $request->id_candidat,
        ]);

        return response()->json([
            'success' => 'Participant added successfully',
            'data' => $electionParticipant,
        ], 201);
    }


    public function getPartcipants(int $id){

        $electionParticipant = ElectionParticipant::where('id_election', $id)
            ->with('candidat')
            ->get();

        return response()->json([
            'success' => 'Participant list',
            'data' => $electionParticipant,
        ], 200);
    }

    public function deletePartcipant(Request $request){
        $request->validate([
            'id_election' => 'required|integer|exists:elections,id',
            'id_candidat' => 'required|integer|exists:candidats,id',
        ]);

        $electionParticipant = ElectionParticipant::where('id_election', $request->id_election)
            ->where('id_candidat', $request->id_candidat)
            ->first();

        if (!$electionParticipant) {
            return response()->json([
                'message' => 'Participant not found',
            ], 404);
        }

        $electionParticipant->delete();

        return response()->json([
            'success' => 'Participant deleted successfully',
        ], 200);
    }


    public function getPartcipantsByCandidat(Request $request){
        $request->validate([
            'id_candidat' => 'required|integer|exists:candidats,id',
        ]);

        $electionParticipant = ElectionParticipant::where('id_candidat', $request->id_candidat)
            ->with('election')
            ->get();

        return response()->json([
            'success' => 'Participant list',
            'data' => $electionParticipant,
        ], 200);
    }
}
