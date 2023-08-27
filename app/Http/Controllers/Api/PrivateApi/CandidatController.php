<?php

namespace App\Http\Controllers\Api\PrivateApi;

use App\Http\Controllers\Api\Services\ActivityController;
use App\Http\Controllers\Api\Services\FollowerController;
use App\Http\Controllers\Api\Services\MeetController;
use App\Http\Controllers\Api\Services\PostController;
use App\Http\Controllers\Controller;
use App\Models\Candidat;
use Illuminate\Http\Request;

class CandidatController extends Controller
{


    //POSTS

    /**
     * @OA\Post(
     *     path="/api/private/candidat/add-post",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Ajouter un post",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(response="200", description="Post ajouté avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */



    public function addPost(Request $request)
    {
        $postcontroller = new PostController();
        return $postcontroller->addPost($request);
    }


    /**
     * @OA\Get(
     *     path="/api/private/candidat/{id}/get-posts",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Récupérer tous les posts d'un candidat",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du candidat",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Liste des posts récupérée avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */

    public function getPosts(int $id)
    {
        $postcontroller = new PostController();
        return $postcontroller->getAllPostByCandidat($id);
    }


    /**
     * @OA\Delete(
     *     path="/api/private/candidat/{id}/delete-post",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Supprimer un post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du post à supprimer",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Post supprimé avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */
    public function deletePost(int $id)
    {
        $postcontroller = new PostController();
        return $postcontroller->deletePost($id);
    }

    //get candidat by commune
    public function getCandidatByCommune(string $commune)
    {
        //refaire la requette en ajoutant les données de l'utilisateur et son parti politique et ses followers
        $candidats = Candidat::join('users', 'candidats.user_id', '=', 'users.id')
            ->where('users.commune', $commune)
            ->select('candidats.*')
            ->with('user', 'partiPolitique', 'followers')->get();

        if ($candidats->count() > 0) {
            return response()->json([
                'data' => $candidats,
                'message' => 'Liste des candidats récupérée avec succès'
            ], 200);
        } else {
            return response()->json(['message' => 'Pas de candidats'], 404);
        }
    }

    //MEETS

    /**
     * @OA\Post(
     *     path="/api/private/candidat/add-meet",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Ajouter une rencontre",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Meet")
     *     ),
     *     @OA\Response(response="200", description="Rencontre ajoutée avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */





    public function addMeet(Request $request)
    {
        $meetcontroller = new MeetController();
        return $meetcontroller->addMeet($request);
    }

    /**
     * @OA\Get(
     *     path="/api/private/candidat/{id}/get-meets",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Récupérer toutes les rencontres d'un candidat",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du candidat",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Liste des rencontres récupérée avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */



    public function getMeets(int $id)
    {
        $meetcontroller = new MeetController();
        return $meetcontroller->getMeets($id);
    }


    /**
     * @OA\Get(
     *     path="/api/private/candidat/search-meets/{val}",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Rechercher des rencontres par valeur",
     *     @OA\Parameter(
     *         name="val",
     *         in="path",
     *         description="Valeur de recherche",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Liste des rencontres trouvées récupérée avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */



    public function searchMeets(string $val)
    {
        $meetcontroller = new MeetController();
        return $meetcontroller->searchMeets($val);
    }


    /**
     * @OA\Delete(
     *     path="/api/private/candidat/{id}/delete-meet",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Supprimer une rencontre",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la rencontre à supprimer",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Rencontre supprimée avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */
    public function deleteMeet(int $id)
    {
        $meetcontroller = new MeetController();
        return $meetcontroller->deleteMeet($id);
    }



    //activities
    /**
     * @OA\Get(
     *     path="/api/private/candidat/{id}/get-activities",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Récupérer toutes les activités d'un candidat",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du candidat",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Liste des activités récupérée avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */


    public function getActivities(int $id)
    {
        $activityController = new ActivityController();

        return $activityController->getAllActivitiesByCandidate($id);
    }


    /**
     * @OA\Post(
     *     path="/api/private/candidat/add-activity",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Ajouter une activité",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Activity")
     *     ),
     *     @OA\Response(response="200", description="Activité ajoutée avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */


    public function addActivity(Request $request)
    {
        $activityController = new ActivityController();

        return $activityController->addActivity($request);
    }


    /**
     * @OA\Put(
     *     path="/api/private/candidat/update-activity/{id}",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Mettre à jour une activité",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de l'activité à mettre à jour",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Activity")
     *     ),
     *     @OA\Response(response="200", description="Activité mise à jour avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */


    public function updateActivity(Request $request, int $id)
    {
        $activityController = new ActivityController();

        return $activityController->update($request, $id);
    }


    /**
     * @OA\Delete(
     *     path="/api/private/candidat/{id}/delete-activity",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Supprimer une activité",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de l'activité à supprimer",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Activité supprimée avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */

    public function deleteActivity(int $id)
    {
        $activityController = new ActivityController();

        return $activityController->destroy($id);
    }



    //candidats



    public function getAllCandidats()
    {
       //selectionner tout les candidats avec leurs users et partis politiques et leurs followers
        $candidats = Candidat::with('user', 'partiPolitique', 'followers')->get();
        if ($candidats->count() > 0) {

            return response()->json([
                'data' => $candidats,
                'message' => 'Liste des candidats récupérée avec succès'
            ], 200);
        } else {
            return response()->json(['message' => 'Pas de candidats'], 404);
        }

    }


    public function getCandidat(int $id)
    {
        $candidat = Candidat::with('user', 'partiPolitique')->find($id);
        if ($candidat) {
            return response()->json([
                'data' => $candidat,
                'message' => 'Candidat récupéré avec succès'
            ], 200);
        } else {
            return response()->json(['message' => 'Candidat non trouvé'], 404);
        }
    }


    //getby nom
    public function searchCandidats(string $val)
    {
        $candidats = Candidat::with('user', 'partiPolitique')->search($val)->get();
        if ($candidats->count() > 0) {
            return response()->json([
                'data' => $candidats,
                'message' => 'Liste des candidats récupérée avec succès'
            ], 200);
        } else {
            return response()->json(['message' => 'Pas de candidats'], 404);
        }
    }


    //followers


    /**
     * @OA\Get(
     *     path="/api/private/candidat/get-my-followers",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Récupérer mes followers",
     *     @OA\Response(response="200", description="Liste des followers récupérée avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */

    public function getMyFollowers()
    {
        $id_user = auth()->user()->id;

        $candidat = Candidat::where('user_id', $id_user)->first();
        return  $candidat->id



    }


    /**
     * @OA\Get(
     *     path="/api/private/candidat/count-my-followers",
     *     tags={"Candidat Authenticated actions"},
     *     summary="Récupérer le nombre de mes followers",
     *     @OA\Response(response="200", description="Nombre de followers récupéré avec succès"),
     *     @OA\Response(response="401", description="Non autorisé")
     * )
     */

    public function countMyFollowers()
    {
        $candidat = auth()->user()->candidat;
        $followercontroller = new FollowerController();
        return $followercontroller->countFollowers($candidat->id);
    }


}
