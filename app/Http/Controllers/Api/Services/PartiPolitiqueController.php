<?php

namespace App\Http\Controllers\Api\Services;

use App\Http\Controllers\Controller;
use App\Models\PartiPolitique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartiPolitiqueController extends Controller
{


    public function getAllPartisPolitiques()
    {
        $partisPolitiques = PartiPolitique::all();
        if ($partisPolitiques->count() > 0){
            return response()->json([
                'data' => $partisPolitiques
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de partis politiques'
            ], 404);
        }
    }

    public function getPartiPolitique(int $id)
    {
        $partiPolitique = PartiPolitique::find($id);
        if (!$partiPolitique) {
            return response()->json([
                'message' => 'Parti politique non trouvé'
            ], 404);
        }
        return response()->json([
            'data' => $partiPolitique
        ], 200);
    }


    public function addPartiPolitique(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'description' => 'required|string',
            'logo' => ['nullable', 'mimes:jpeg,png,gif,mp4,mov,avi']
        ]);

        //verifier si le parti politique existe deja
        $partiPolitique = PartiPolitique::where('nom', $request->input('nom'))->first();
        if ($partiPolitique) {
            return response()->json([
                'message' => 'Parti politique existe deja'
            ], 409);
        }

        if ($request->hasFile('logo')){
            $file = $request->file('logo');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $file->move('storage/partisPolitiques', $fileName);
            $nameToFront = 'storage/partisPolitiques/' . $fileName;
        }
        else {
            $nameToFront = 'partisPolitiques/default.jpg';
        }

        $partiPolitique = new PartiPolitique();
        $partiPolitique->nom = $request->input('nom');
        $partiPolitique->description = $request->input('description');
        $partiPolitique->logo = $nameToFront;
        $partiPolitique->save();

        return response()->json([
            'success' => 'Parti politique added successfully'
        ], 200);
    }


    public function updatePartiPolitiqueData(int $id, Request $request)
    {
        $partiPolitique = PartiPolitique::find($id);
        if (!$partiPolitique) {
            return response()->json([
                'message' => 'Parti politique non trouvé'
            ], 404);
        }

        $request->validate([
            'nom' => 'required|string',
            'description' => 'required|string',
        ]);

        $partiPolitique->nom = $request->input('nom');
        $partiPolitique->description = $request->input('description');
        $partiPolitique->save();

        return response()->json([
            'message' => 'Parti politique modifié avec succès'
        ], 200);


    }


    public function updatePartiPolitiqueLogo(int $id, Request $request)
    {
        $partiPolitique = PartiPolitique::find($id);
        if (!$partiPolitique) {
            return response()->json([
                'message' => 'Parti politique non trouvé'
            ], 404);
        }

        $request->validate([
            'logo' => 'required|mimes:jpeg,png,gif,mp4,mov,avi',
        ]);


        $file = $request->file('logo');
        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = $fileName . '_' . time() . '.' . $extension;
        $file->move('storage/partisPolitiques', $fileName);
        $nameToFront = 'storage/partisPolitiques/' . $fileName;

        Storage::delete($partiPolitique->logo);
        $partiPolitique->logo = $nameToFront;
        $partiPolitique->save();

        return response()->json([
            'message' => 'Logo modifié avec succès'
        ], 200);
    }


    public function deletePartiPolitique(int $id)
    {
        $partiPolitique = PartiPolitique::find($id);
        if (!$partiPolitique) {
            return response()->json([
                'message' => 'Parti politique non trouvé'
            ], 404);
        }
        Storage::delete($partiPolitique->logo);

        $partiPolitique->delete();
        return response()->json([
            'message' => 'Parti politique supprimé avec succès'
        ], 200);
    }


    public function searchPartiPolitique(Request $request)
    {
        $request->validate([
            'val' => 'required|string',
        ]);

        $val = $request->input('val');
        $partiPolitiques = PartiPolitique::search($val)->get();
        if ($partiPolitiques->count() > 0) {
            return response()->json([
                'data' => $partiPolitiques
            ], 200);
        } else {
            return response()->json([
                'message' => 'Pas de partis politiques'
            ], 404);
        }
    }


}
