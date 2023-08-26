<?php

namespace App\Http\Controllers\Api\Services;

use App\Http\Controllers\Controller;
use App\Models\ResultatsProgrammes;
use Illuminate\Http\Request;

class ResultatsProgrammesController extends Controller
{

    /**
     * @OA\Post(
     *    path="/api/private/user/add-programme-reaction",
     *    tags={"User Authenticated actions"},
     *    summary="Add a reaction to a programme",
     *    description="Add a reaction to a programme",
     *    operationId="addProgrammeReaction",
     *    @OA\RequestBody(
     *      required=true,
     *      description="Programme reaction object that needs to be added to the store",
     *      @OA\JsonContent(ref="#/components/schemas/ResultatProgramme")
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Programme reaction added successfully",
     *      @OA\JsonContent(
     *        @OA\Property(property="success", type="string", example="Programme reaction added successfully")
     *      )
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthorized",
     *      @OA\JsonContent(
     *        @OA\Property(property="error", type="string", example="Unauthenticated.")
     *      )
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Message d'erreur pour les champs non valides",
     *      @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="Les données fournies sont invalides"),
     *        @OA\Property(property="errors", type="object")
     *      )
     *    )
     * )
     */

    public function addProgrammeReaction(Request $request)
    {
        $request->validate([
            'id_programme' => 'required|integer|exists:programmes,id',
            'id_user' => 'required|integer|exists:users,id',
            'avis' => 'required|boolean',
        ]);

        $programmeReaction = new resultatsProgrammes();

        $programmeReaction->id_programme = $request->input('id_programme');
        $programmeReaction->id_user = $request->input('id_user');
        $programmeReaction->avis = $request->input('avis');
        $programmeReaction->save();

        return response()->json([
            'success' => 'Programme reaction added successfully'
        ], 200);
    }

}
