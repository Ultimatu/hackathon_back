<?php

namespace App\Http\Controllers\Api\Services;

use App\Http\Controllers\Controller;
use App\Models\programmes;
use Illuminate\Http\Request;

class ProgrammesController extends Controller
{

    public function addProgramme(Request $request)
    {
        $request->validate([
            'id_candidat' => 'required|exists:candidats,id',
            'titre' => 'required|string',
            'description' => 'required|string',
            'url_media' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|video|mimes:mp4,mov,ogg,qt'
        ]);

        $programme = Programmes::where('id_candidat', $request->id_candidat, 'titre', $request->titre, 'description', $request->description)->first();
        if ($programme) {
            return response()->json([
                'error' => 'Vous avez deja ajouté ce programme',
            ], 400);
        }

        $programme = programmes::create([
            'id_candidat' => $request->id_candidat,
            'titre' => $request->titre,
            'description' => $request->description,
            'url_media' => $request->url_media,
        ]);

        return response()->json([
            'success' => 'Programme ajouté',
            'programme' => $programme,
        ], 201);
    }


    public function getProgrammes(int $id_candidat)
    {
        $programmes = Programmes::where('id_candidat', $id_candidat)->get();
        if ($programmes->isEmpty()) {
            return response()->json([
                'message' => 'Aucun programme trouvé',
                'data' => $programmes,
            ], 404);
        }
        return response()->json([
            'success' => 'Liste des programmes',
            'data' => $programmes,
        ], 200);
    }

    public function searchProgrammes(string $val)
    {
        $programmes = Programmes::search($val)->get();
        if ($programmes->isEmpty()) {
            return response()->json([
                'message' => 'Aucun programme trouvé',
                'data' => $programmes,
            ], 404);
        }
        return response()->json([
            'success' => 'Liste des programmes',
            'data' => $programmes,
        ], 200);
    }

    public function updateProgramme(int $id, Request $request)
    {
        $programme = Programmes::find($id);
        if (!$programme) {
            return response()->json([
                'message' => 'Programme non trouvé'
            ], 404);
        }

        $request->validate([
            'id_candidat' => 'required|exists:candidats,id',
            'titre' => 'required|string',
            'description' => 'required|string',
            'url_media' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|video|mimes:mp4,mov,ogg,qt'
        ]);

        $programme->titre = $request->input('titre');
        $programme->description = $request->input('description');
        $programme->url_media = $request->input('url_media');

        $programme->save();

        return response()->json([
            'success' => 'Programme modifié avec succès'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(programmes $programmes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(programmes $programmes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, programmes $programmes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(programmes $programmes)
    {
        //
    }
}
