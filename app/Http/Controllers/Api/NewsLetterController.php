<?php

namespace App\Http\Controllers\Api;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsLetterController extends Controller
{


    public function addEmail(Request $request){
        $request->validate([
            'email'=>'required|email|unique:newsletter,email',
        ]);

        $newsletter = Newsletter::create([
            'email'=>$request->email,
            'is_active'=>true,
        ]);

        //essaie d'envoyer un mail à l'utilisateur
        $to = $request->email;
        $subject = "Bienvenue à la newsletter";
        $txt = "Bienvenue à la newsletter";
        try {
            mail($to,$subject,$txt);
            $fake = null;
        } catch (\Throwable $th) {
           $fake = $th;
        }

        return response()->json([
            'message'=>'Votre email a été ajouté à la newsletter',
            'reponse'=>$fake

        ],201);
    }


    public function deleteEmail(Request $request){
        $request->validate([
            'email'=>'required|email|exists:newsletter,email',
        ]);

        $newsletter = Newsletter::where('email',$request->email)->first();
        $newsletter->delete();

        return response()->json([
            'message'=>'Votre email a été supprimé de la newsletter',
        ],200);
    }
}
