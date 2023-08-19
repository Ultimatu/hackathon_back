<?php

namespace App\Http\Controllers\Api\PublicApi;

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\CommentaireController;
use App\Http\Controllers\Api\CommentaireRepliqueController;
use App\Http\Controllers\Api\ElectionController;
use App\Http\Controllers\Api\ElectionParticipantController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\MeetParticipantController;
use App\Http\Controllers\Api\PartiPolitiqueController;
use App\Http\Controllers\Api\PrivateApi\CandidatController;
use App\Http\Controllers\Api\SondageController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations\OpenApi;
use OpenApi\Generator;
class PublicController extends Controller
{


    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="API Documentation",
     *      description="Documentation des API de l'application Les Innovateurs. Cette documentation fournit des informations détaillées sur les différentes API disponibles pour gérer les utilisateurs, les élections, les candidats, les activités, les sondages et d'autres fonctionnalités de l'application.",
     *
     *      @OA\License(
     *          name="URL Accueil",
     *          url="https://lesinnovateurs.me"
     *      ),
     *     @OA\Server(
     *         description="Environnement local",
     *         url="http://localhost:8000/api/documentation"
     *     ),
     *     @OA\Server(
     *         description="Environnement de production",
     *         url="https://lesinnovateurs.me/api/documentation"
     *     ),
     * @OA\SecurityScheme(
     *    type="http",
     *   description="Authentification par token",
     *   scheme="bearer",
     *
     * )
     * )
     */



    public function getAllActvities(){

        $activities = new ActivityController();

        return $activities->getAllActivities();
    }


    /**
     * @OA\Get(
     *     path="/api/public/activity/{id}",
     *     tags={"Public API"},
     *     summary="Get activity by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Activity id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Activity retrieved successfully"),
     *     @OA\Response(response="404", description="Activity not found")
     * )
     */

    public function getActivityDatas(int $id){
        $activity = new ActivityController();

        return $activity->getActivity($id);
    }


    /**
     * @OA\Get(
     *     path="/api/public/candidat/{id}/get-activities",
     *     tags={"Public API"},
     *     summary="Avoir les activités d'un candidat",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Candidat id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Activities retrieved successfully"),
     *     @OA\Response(response="404", description="Candidat not found")
     * )
     */

    public function getCandidatsActivities($id){
        $activity = new CandidatController();

        return $activity->getActivities($id);
    }

    /**
     * @OA\Get(
     *     path="/api/post/{id}/comments",
     *     tags={"Public API"},
     *     summary="Get all comments of a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Comments retrieved successfully"),
     *     @OA\Response(response="404", description="Post not found")
     * )
     */


    public function getPostComments($id){
        $comments = new CommentaireController();

        return $comments->getPostCommentaires($id);
    }



    /**
     * @OA\Get(
     *     path="/api/comment/{id}/replies",
     *     tags={"Public API"},
     *     summary="Get all comments of a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Comments retrieved successfully"),
     *     @OA\Response(response="404", description="Post not found")
     * )
     */
    public function getCommentsReplique($id){
        $repliques = new CommentaireRepliqueController();
        return $repliques->getCommentaireReplique($id);
    }


    /**
     * @OA\Get(
     *     path="/api/comment/{id}/all-replies",
     *     tags={"Public API"},
     *     summary="Get all comments of a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Comments retrieved successfully"),
     *     @OA\Response(response="404", description="Post not found")
     * )
     */

    public function getCommentAllRepliques(int $id)
    {
        $commentaireRepliqueController = new CommentaireRepliqueController();

        return $commentaireRepliqueController->getAllCommentaireRepliquesByCommentaire($id);
    }



    /**
     * @OA\Get(
     *     path="/api/elections",
     *     tags={"Public API"},
     *     summary="Get all elections",
     *     @OA\Response(response="200", description="Elections retrieved successfully"),
     *     @OA\Response(response="404", description="Elections not found")
     * )
     */
    public function getAllElections(){
        $elections = new ElectionController();

        return $elections->getAllElections();
    }


    /**
     * @OA\Get(
     *     path="/api/election/{id}",
     *     tags={"Public API"},
     *     summary="Get election by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Election id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Election retrieved successfully"),
     *     @OA\Response(response="404", description="Election not found")
     * )
     */

    public function getElectionData(int $id)
    {
        $elections = new ElectionController();

        return $elections->getById($id);

    }


    /**
     * @OA\Get(
     *     path="/api/election/{id}/participants",
     *     tags={"Public API"},
     *     summary="Get all participants of an election",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Election id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Participants retrieved successfully"),
     *     @OA\Response(response="404", description="Election not found")
     * )
     */

    public function getElectionParticipants($id)
    {
        $elections = new ElectionParticipantController();

        return $elections->getPartcipants($id);
    }



    /**
     * @OA\Get(
     *     path="/api/post/{id}/likes",
     *     tags={"Public API"},
     *     summary="Get all likes of a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Likes retrieved successfully"),
     *     @OA\Response(response="404", description="Post not found")
     * )
     */

    public function getAllPostLikes(int $id)
    {
        $likeController = new LikeController();

        return $likeController->getPostLikes($id);
    }



    /**
     * @OA\Get(
     *     path="/api/public/meets",
     *     operationId="getMeets",
     *     tags={"Public API"},
     *     summary="Get meets",
     *     description="Get a list of meets.",
     *     @OA\Response(response="200", description="Successful response"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function getMeets(Request $request)
    {
        $meetController = new MeetParticipantController();

        return $meetController->getMeets($request);
    }



    /**
     * @OA\Get(
     *     path="/api/public/sondages",
     *     tags={"Public API"},
     *     summary="Avoir tous les sondages",
     *     @OA\Response(response="200", description="Sondages retrieved successfully"),
     *     @OA\Response(response="404", description="Sondages not found")
     * )
     */
    public function getAllSondages(){
        $sondages = new SondageController();

        return $sondages->getAllSondages();
    }



    /**
     * @OA\Get(
     *     path="/api/public/sondages/{commune}",
     *     tags={"Public API"},
     *     summary="Avoir les sondages d'une commune",
     *     @OA\Parameter(
     *         name="commune",
     *         in="path",
     *         description="Commune name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Sondages retrieved successfully"),
     *     @OA\Response(response="404", description="Commune not found")
     * )
     */

    public function getAllSondageByCommune(string $commune){

        $sondages = new SondageController();

        return $sondages->getAllSondageByCommune($commune);
    }


    /**
     * @OA\Get(
     *     path="/api/public/parti-politiques",
     *     tags={"Public API"},
     *     summary="Récupérer la liste de tous les partis politiques",
     *     @OA\Response(response="200", description="Liste des partis politiques récupérée avec succès"),
     * )
     */

    public function getAllPartiPolitiques()
    {
        $partiPolitiqueController = new PartiPolitiqueController();

        return $partiPolitiqueController->getAllPartisPolitiques();
    }

    /**
     * @OA\Get(
     *     path="/api/public/parti-politique/{id}",
     *     tags={"Public API"},
     *     summary="Récupérer les détails d'un parti politique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du parti politique",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Détails du parti politique récupérés avec succès"),
     *     @OA\Response(response="404", description="Parti politique non trouvé"),
     * )
     */

    public function getPartiPolitique($id)
    {
        $partiPolitiqueController = new PartiPolitiqueController();

        return $partiPolitiqueController->getPartiPolitique($id);
    }


}


