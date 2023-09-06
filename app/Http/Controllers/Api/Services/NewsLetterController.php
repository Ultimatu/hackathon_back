<?php

namespace App\Http\Controllers\Api\Services;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $message = "Bienvenue à la newsletter";
        Mail::to($request->email)->send(new \App\Mail\SendToUserMail());

        return response()->json([
            'success'=>'Votre email a été ajouté à la newsletter',
        ],201);
    }


    public function deleteEmail(Request $request){
        $request->validate([
            'email'=>'required|email|exists:newsletter,email',
        ]);

        $newsletter = Newsletter::where('email',$request->email)->first();
        $newsletter->delete();


        return response()->json([
            'success'=>'Votre email a été supprimé de la newsletter',
        ],200);
    }
}
