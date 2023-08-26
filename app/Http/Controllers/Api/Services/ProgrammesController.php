<?php

namespace App\Http\Controllers\Api\Services;

use App\Http\Controllers\Controller;
use App\Models\Programmes;
use Illuminate\Http\Request;

class ProgrammesController extends Controller
{

    /**
     * @OA\Post(
     *   path="/api/private/candidat/add-programme",
     *   tags={"Candidat Authenticated actions"},
     *   summary="Ajouter un programme",
     *   description="Ajouter un programme",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       ref="#/components/schemas/Programme"
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Programme ajouté avec succès",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="Programme ajouté"),
     *       @OA\Property(property="programme", type="object", ref="#/components/schemas/Programme")
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Bad request",
     *     @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Vous avez deja ajouté ce programme")
     *     )
     *   )
     * )
     */

    public function addProgramme(Request $request)
    {
        $request->validate([
            'id_candidat' => 'required|exists:candidats,id',
            'titre' => 'required|string',
            'description' => 'required|string',
            'url_media' => 'nullable|string|image|mimes:jpeg,png,jpg,gif,svg|video|mimes:mp4,mov,ogg,qt'
        ]);

        $programme = Programmes::where('id_candidat', $request->id_candidat, 'titre', $request->titre, 'description', $request->description)->first();
        if ($programme) {
            return response()->json([
                'error' => 'Vous avez deja ajouté ce programme',
            ], 400);
        }
        if ($request->hasFile('url_media')) {
            $file = $request->file('url_media');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $file->move('storage/programmes', $fileNameToStore);
            $nameToFront = 'storage/programmes/' . $fileNameToStore;
        } else if ($request->input('url_media') != null && is_string($request->input('url_media'))) {
            $nameToFront = $request->input('url_media');
        } else {
            $nameToFront = 'default_programme.png';
        }

        $programme = programmes::create([
            'id_candidat' => $request->id_candidat,
            'titre' => $request->titre,
            'description' => $request->description,
            'url_media' => $nameToFront,
        ]);

        return response()->json([
            'success' => 'Programme ajouté',
            'programme' => $programme,
        ], 201);
    }



    /**
     * @OA\Get(
     *     path="not-defined-yet",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Récupérer  les  programmes d'un candidat",
     *     description="Récupérer les  programmes d'un candidat",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du candidat lié au programme",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Programme récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Programme récupéré"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Programme")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Programme non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Programme non trouvé")
     *         )
     *     )
     * )
     */
    public function getProgrammes(int $id_candidat)
    {
        $programmes = Programmes::where('id_candidat', $id_candidat)->with('resultatsProgrammes')->get();
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


    /**
     * @OA\Get(
     *     path="/api/public/programme/{val}",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Rechercher un programme",
     *     description="Rechercher un programme",
     *     @OA\Parameter(
     *         name="val",
     *         in="path",
     *         description="valeur de la recherche du programme",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Programme récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Programme récupéré"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Programme")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Programme non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Programme non trouvé")
     *         )
     *     )
     * )
     */
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


    //update programme
    /**
     * @OA\Put(
     *     path="/api/private/candidat/update-programme/{id}",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Modifier un programme",
     *     description="Modifier un programme",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du programme",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *  @OA\RequestBody(
     *   required=true,
     *  @OA\JsonContent(
     *   ref="#/components/schemas/Programme"
     *  )
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="Programme modifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Programme modifié")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Programme non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Programme non trouvé")
     *         )
     *     )
     * )
     */
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
            'url_media' => 'nullable|string|image|mimes:jpeg,png,jpg,gif,svg|video|mimes:mp4,mov,ogg,qt'
        ]);
        if ($request->hasFile('url_media')) {
            $file = $request->file('url_media');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $file->move('storage/programmes', $fileNameToStore);
            $nameToFront = 'storage/programmes/' . $fileNameToStore;
        } else if ($request->input('url_media') != null && is_string($request->input('url_media'))) {
            $nameToFront = $request->input('url_media');
        } else {
            $nameToFront = $programme->url_media;
        }

        $programme->titre = $request->input('titre');
        $programme->description = $request->input('description');
        $programme->url_media = $nameToFront;

        $programme->save();

        return response()->json([
            'success' => 'Programme modifié avec succès'
        ], 200);
    }


    /**
     * @OA\Delete(
     *     path="/api/private/candidat/delete-programme/{id}",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Supprimer un programme",
     *     description="Supprimer un programme",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du programme",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Programme supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Programme supprimé")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Programme non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Programme non trouvé")
     *         )
     *     )
     * )
     */
    public function destroy(int $id)
    {
        $programme = Programmes::find($id);
        if (!$programme) {
            return response()->json([
                'message' => 'Programme non trouvé'
            ], 404);
        }
        $programme->delete();
        return response()->json([
            'success' => 'Programme supprimé avec succès'
        ], 200);
    }


    /**
     * @OA\Get(
     *     path="/api/private/candidat/my-programmes",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Récupérer tous les programmes d'un candidat",
     *     description="Récupérer tous les programmes d'un candidat",
     *
     *
     *     @OA\Response(
     *         response=200,
     *         description="Programme récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Programme récupéré"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Programme")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Programme non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Programme non trouvé")
     *         )
     *     )
     * )
     */

    public function getAllMyProgrammes()
    {
        $programmes = Programmes::where('id_candidat', auth()->user()->candidat->id)->with('resultatsProgrammes')->get();
        if ($programmes->isEmpty()) {
            return response()->json([
                'message' => 'Aucun programme trouvé',
                'data' => $programmes,
            ], 200);
        }
        return response()->json([
            'success' => 'Liste des programmes',
            'data' => $programmes,
        ], 200);
    }



    /**
     * @OA\Get(
     *     path="/api/private/user/programmes-not-reacted",
     *     tags={"User Authenticated actions"},
     *     summary="Récupérer tous les programmes d'un candidat",
     *     description="Récupérer tous les programmes d'un candidat",
     *@OA\Parameter(
     *         name="id_candidat",
     *         in="path",
     *         description="ID du candidat lié au programme",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Programme récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Programme récupéré"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Programme")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Programme non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Programme non trouvé")
     *         )
     *     )
     * )
     */

    public function getAllProgrammesNotReacted(int $id_candidat)
    {
        $programmes = Programmes::where('id_candidat', $id_candidat)->whereDoesntHave('resultatsProgrammes', function ($query) {
            $query->where('id_user', auth()->user()->id);
        })->get();
        if ($programmes->isEmpty()) {
            return response()->json([
                'message' => 'Aucun programme trouvé',
                'data' => $programmes,
            ], 200);
        }
        return response()->json([
            'success' => 'Liste des programmes',
            'data' => $programmes,
        ], 200);
    }




    /**
     * @OA\Get(
     *     path="/api/public/all-programmes/{id_candidat}",
     *     tags={"Public API"},
     *     summary="Récupérer tous les programmes d'un candidat",
     *     description="Récupérer tous les programmes d'un candidat",
     *     @OA\Parameter(
     *         name="id_candidat",
     *         in="path",
     *         description="ID du candidat lié au programme",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Programme récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Programme récupéré"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Programme")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Programme non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Programme non trouvé")
     *         )
     *     )
     * )
     */

    public function getAllProgrammes(int $id_candidat)
    {
        $programmes = Programmes::where('id_candidat', $id_candidat)->with('resultatsProgrammes')->get();
        if ($programmes->isEmpty()) {
            return response()->json([
                'message' => 'Aucun programme trouvé',
                'data' => $programmes,
            ], 200);
        }
        return response()->json([
            'success' => 'Liste des programmes',
            'data' => $programmes,
        ], 200);
    }



}
