<?php

namespace App\Http\Controllers\Api\Services;

use App\Http\Controllers\Controller;
use App\Models\Elections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ElectionController extends Controller
{

    public function addElection(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'nom' => 'required|string',
            'duration' => 'required|string',
            'image_url'=> 'nullable',
            'type' => 'required|string', // 'presidentielle' ou 'legislative
            'status' => 'required|string', // 'en cours' ou 'terminée
            'banner_url'=> 'nullable',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
        ]);
        $request['image_url']??= 'elections/default.jpg';
        $request['banner_url']??= 'elections/default.jpg';
        $election = new Elections();

        $election->description = $request->input('description');
        $election->nom = $request->input('nom');
        $election->duration = $request->input('duration');
        $election->status =
        $election->type = $request->input('type');
        $election->date_debut = $request->input('date_debut');
        $election->date_fin = $request->input('date_fin');
        $election->image_url = $request->input('image_url');
        $election->banner_url = $request->input('banner_url');
        $election->save();

        return response()->json(['success' => 'Election ajoutée avec succès'], 200);
    }

    public function updateData(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'nom' => 'required|string',
            'type' => 'required|string', // 'presidentielle' ou 'legislative
            'status' => 'required|string', // 'en cours' ou 'terminée
            'duration' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            "image_url" => "nullable",
            "banner_url" => "nullable",
        ]);


        $election = Elections::find($id);
        if (!$election) {
            return response()->json(['message' => 'Election non trouvée'], 200);
        }

        $election->description = $request->input('description');
        $election->type = $request->input('type');
        $election->nom = $request->input('nom');
        $election->duration = $request->input('duration');
        $election->date_debut = $request->input('date_debut');
        $election->date_fin = $request->input('date_fin');
        $election->status = $request->input('status');
        $election->image_url = $request->input('image_url');
        $election->banner_url = $request->input('banner_url');

        $election->save();

        return response()->json(['success' => 'Election modifiée avec succès'], 200);
    }

    /*
    public function updateBanner(int $id, Request $request){

        $election = Elections::find($id);
        if (!$election) {
            return response()->json(['message' => 'Election non trouvée'], 200);
        }



        $request->validate([
            'banner_url'=> 'required|string|image|mimes:jpeg,png,jpg,gif,svg|',
        ]);
        if (is_string($request->banner_url) && $request->banner_url != 'null') {

            $election->banner_url = $request->banner_url;
            $election->save();
            return response()->json(['success' => 'Election modifiée avec succès'], 200);
        }

        $file = $request->file('banner_url');
        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
        $file->move('storage/elections', $fileNameToStore);
        $nameToFront = 'storage/elections/' . $fileNameToStore;
        if (file_exists($election->banner_url) && $election->banner_url != 'elections/default.jpg')
            Storage::delete($election->banner_url);
        $election->banner_url = $nameToFront;
        $election->save();

        return response()->json(['success' => 'Election modifiée avec succès'], 200);

    }

    public function updateImage(int $id, Request $request)
    {

        $election = Elections::find($id);
        if (!$election) {
            return response()->json(['message' => 'Election non trouvée'], 200);
        }


        $request->validate([
            'image_url' => 'required|string',
        ]);

        if (is_string($request->image_url) && $request->image_url != 'null') {

            $election->image_url = $request->image_url;
            $election->save();
            return response()->json(['success' => 'Election modifiée avec succès'], 200);
        }
        $file = $request->file('image_url');
        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
        $file->move('storage/elections', $fileNameToStore);
        $nameToFront = 'storage/elections/' . $fileNameToStore;
        if (file_exists($election->image_url) && $election->image_url != 'elections/default.jpg')
            Storage::delete($election->image_url);

        $election->image_url = $nameToFront;
        $election->save();

        return response()->json(['success' => 'Election modifiée avec succès'], 200);
    } */


    public function delete(int $id){

        $election = Elections::find($id);
        if (!$election) {
            return response()->json(['message' => 'Election non trouvée'], 200);
        }
        if (file_exists($election->image_url) && $election->image_url != 'elections/default.jpg')
            Storage::delete($election->image_url);
        if (file_exists($election->banner_url) && $election->banner_url != 'elections/default.jpg')
            Storage::delete($election->banner_url);
        $election->delete();

        return response()->json(['success' => 'Election supprimée avec succès'], 200);
    }

    public function getAllElections(){
        $elections = Elections::all();
        $elections->load('participants', 'participants.candidat', 'participants.candidat.user', 'participants.candidat.parti_politique');

        if ($elections->count() > 0) {
            return response()->json([
                'data' => $elections
            ], 200);
        } else {
            return response()->json([
                'message' => 'Pas d\'election programmée',
            ], 200);
        }
    }


    public function getById(int $id){
        $elections = Elections::find($id);
        if ($elections) {
            return response()->json([
                'data' => $elections
            ], 200);
        } else {
            return response()->json([
                'message' => 'Pas de elections avec cet  id'
            ], 200);
        }

    }

}
