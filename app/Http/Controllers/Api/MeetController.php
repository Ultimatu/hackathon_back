<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meet;
use Illuminate\Http\Request;

class MeetController extends Controller
{

    public function addMeet(Request $request){
        $request->validate([
            'id_candidat'=>'required|integer|exists:candidats,id',
            'titre'=>'required|string',
            'description'=>'required|string',
            'url_media'=>'required|string',
        ]);

        $meet = Meet::create([
            'id_candidat'=>$request->id_candidat,
            'titre'=>$request->titre,
            'description'=>$request->description,
            'url_media'=>$request->url_media,
        ]);

        return response()->json([
            'success'=>'Ajout avec succes',
            'data'=>$meet,
        ],201);

    }


    public function getMeets(int $id_candidat){


        $meet = Meet::where('id_candidat',$id_candidat)
            ->get();

        return response()->json([
            'success'=>'Liste des meet',
            'data'=>$meet,
        ],200);

    }


    public function searchMeets(string $val){

        $meet = Meet::search($val)
            ->get();

        if ($meet->isEmpty()) {
            return response()->json([
                'message'=>'Aucun meet trouvé',
                'data'=>$meet,
            ],404);
        }

        return response()->json([
            'success'=>'Liste des meet',
            'data'=>$meet,
        ],200);

    }



    public function deleteMeet(int $id){


        $meet = Meet::where('id',$id)
            ->first();

        if (!$meet) {
            return response()->json([
                'message'=>'Meet non trouvé',
            ],404);
        }

        $meet->delete();

        return response()->json([
            'success'=>'Meet supprimé avec succes',
        ],200);

    }



    public function updateMeet(Request $request){
        $request->validate([
            'id_meet'=>'required|integer|exists:meets,id',
            'titre'=>'required|string',
            'description'=>'required|string',
            'url_media'=>'required|string',
        ]);

        $meet = Meet::where('id',$request->id_meet)
            ->first();

        if (!$meet) {
            return response()->json([
                'message'=>'Meet non trouvé',
            ],404);
        }

        $meet->update([
            'titre'=>$request->titre,
            'description'=>$request->description,
            'url_media'=>$request->url_media,
        ]);

        return response()->json([
            'success'=>'Meet modifié avec succes',
            'data'=>$meet,
        ],200);

    }


}
