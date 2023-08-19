<?php

namespace App\Http\Controllers\Api;

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
            'image_url'=> ['image','mimes:jpeg,png,jpg,gif,svg', 'nullable'],
            'banner_url'=> ['image','mimes:jpeg,png,jpg,gif,svg', 'nullable'],
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
        ]);

        $election = new Elections();

        $election->description = $request->input('description');
        $election->nom = $request->input('nom');
        $election->duration = $request->input('duration');
        $election->date_debut = $request->input('date_debut');
        $election->date_fin = $request->input('date_fin');


        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $file->move('storage/elections', $fileNameToStore);
            $nameToFront = 'storage/elections/' . $fileNameToStore;
            $election->image_url = $nameToFront;
        }
        else{
            $election->image_url = 'storage/elections/default-image.png';
        }

        if ($request->hasFile('banner_url')) {
            $file = $request->file('banner_url');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $file->move('storage/elections', $fileNameToStore);
            $nameToFront = 'storage/elections/' . $fileNameToStore;
            $election->banner_url = $nameToFront;
        }
        else{
            $election->banner_url = 'storage/elections/default-banner.png';
        }

        return response()->json(['message' => 'Election ajoutée avec succès'], 200);
    }

    public function updateData(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'nom' => 'required|string',
            'duration' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
        ]);


        $election = Elections::find($id);
        if (!$election) {
            return response()->json(['message' => 'Election non trouvée'], 404);
        }

        $election->description = $request->input('description');
        $election->nom = $request->input('nom');
        $election->duration = $request->input('duration');
        $election->date_debut = $request->input('date_debut');
        $election->date_fin = $request->input('date_fin');

        $election->save();

        return response()->json(['message' => 'Election modifiée avec succès'], 200);
    }


    public function updateBanner(int $id, Request $request){

        $election = Elections::find($id);
        if (!$election) {
            return response()->json(['message' => 'Election non trouvée'], 404);
        }


        $request->validate([
            'banner_url'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|',
        ]);

        $file = $request->file('banner_url');
        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
        $file->move('storage/elections', $fileNameToStore);
        $nameToFront = 'storage/elections/' . $fileNameToStore;
        Storage::delete($election->banner_url);
        $election->banner_url = $nameToFront;
        $election->save();

        return response()->json(['message' => 'Election modifiée avec succès'], 200);

    }

    public function updateImage(int $id, Request $request)
    {

        $election = Elections::find($id);
        if (!$election) {
            return response()->json(['message' => 'Election non trouvée'], 404);
        }


        $request->validate([
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|',
        ]);

        $file = $request->file('image_url');
        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
        $file->move('storage/elections', $fileNameToStore);
        $nameToFront = 'storage/elections/' . $fileNameToStore;
        Storage::delete($election->image_url);
        $election->image_url = $nameToFront;
        $election->save();

        return response()->json(['message' => 'Election modifiée avec succès'], 200);
    }


    public function delete(int $id){

        $election = Elections::find($id);
        if (!$election) {
            return response()->json(['message' => 'Election non trouvée'], 404);
        }
        Storage::delete($election->image_url);
        Storage::delete($election->banner_url);
        $election->delete();

        return response()->json(['message' => 'Election supprimée avec succès'], 200);
    }

    public function getAllElections(){
        $elections = Elections::all();
        if ($elections->count() > 0) {
            $electionspaginates = Elections::paginate(10);
            return response()->json([
                'data' => $electionspaginates
            ], 200);
        } else {
            return response()->json([
                'message' => 'Pas d\'election programmée',
            ], 404);
        }
    }


    public function getById(int $id){
        $elections = Elections::find($id);
        if ($elections->count() > 0) {
            return response()->json([
                'data' => $elections
            ], 200);
        } else {
            return response()->json([
                'message' => 'Pas de elections avec cet  id'
            ], 404);
        }
    }

}
