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
use App\Http\Controllers\Api\TypeSondageController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NewsLetterController;
use Illuminate\Http\Request;
use OpenApi\Annotations\OpenApi;
use OpenApi\Generator;
class PublicController extends Controller
{





    /**
     * @OA\Get(
     *     path="/api/public/activities",
     *     tags={"Public API"},
     *     summary="Get all activities",
     *     @OA\Response(response="200", description="Activities retrieved successfully"),
     *     @OA\Response(response="404", description="Activities not found")
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
     *     path="/api/public/candidats",
     *     tags={"Public API"},
     *     summary="Get all candidats",
     *     @OA\Response(response="200", description="Candidats retrieved successfully"),
     *     @OA\Response(response="404", description="Candidats not found")
     * )
     */
    public function getAllcandidats(){
        $candidats = new CandidatController();

        return $candidats->getAllCandidats();
    }


    /**
     * @OA\Get(
     *     path="/api/public/candidat/{id}",
     *     tags={"Public API"},
     *     summary="Get  candidat datas",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id candidat",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Candidats retrieved successfully"),
     *     @OA\Response(response="404", description="Candidats not found")
     * )
     */

    public function getCandidatById(int $id){
        $candidats = new CandidatController();

        return $candidats->getCandidat($id);
    }


    /**
     * @OA\Get(
     *     path="/api/public/candidats/search/{val}",
     *     tags={"Public API"},
     *     summary="Get all candidats by value",
     *     @OA\Parameter(
     *         name="search",
     *         in="path",
     *         description="valeur de recherche de  candidat",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Candidats retrieved successfully"),
     *     @OA\Response(response="404", description="Candidats not found")
     * )
     */


    public function searchCandidats(string $val){
        $candidats = new CandidatController();

        return $candidats->searchCandidats($val);
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
     *     path="/api/public/post/{id}/comments",
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
     *     path="/api/public/comment/{id}/replies",
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
     *     path="/api/public/comment/{id}/all-replies",
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
     *     path="/api/public/elections",
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
     *     path="/api/public/election/{id}",
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
     *     path="/api/public/election/{id}/participants",
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
     *     path="/api/public/post/{id}/likes",
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
     *     path="/api/public/meets/{id_user}",
     *     operationId="getMeets",
     *     tags={"Public API"},
     *     summary="Get meets",
     *    @OA\Parameter(
     *        name="id_user",
     *       in="path",
     *      description="User id",
     *
     *    required=true,
     *    @OA\Schema(type="integer")
     * ),
     *
     *
     *     description="Get a list of meets.",
     *     @OA\Response(response="200", description="Successful response"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function getMeets(int $id_user)
    {
        $meetController = new MeetParticipantController();

        return $meetController->getMeets($id_user);
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


    //types sondages

    /**
     * @OA\Get(
     *     path="/api/public/types-sondages",
     *     tags={"Public API"},
     *     summary="Récupérer la liste de tous les types de sondages",
     *     @OA\Response(response="200", description="Liste des types de sondages récupérée avec succès"),
     * )
     */

    public function getAllTypesSondages()
    {
        $typeSondageController = new TypeSondageController();

        return $typeSondageController->getAllTypeSondages();
    }


    /**
     * @OA\Get(
     *     path="/api/public/type-sondage/{id}",
     *     tags={"Public API"},
     *     summary="Récupérer les détails d'un type de sondage",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du type de sondage",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Détails du type de sondage récupérés avec succès"),
     *     @OA\Response(response="404", description="Type de sondage non trouvé"),
     * )
     */

    public function getTypeSondageById($id)
    {
        $typeSondageController = new TypeSondageController();

        return $typeSondageController->getTypeSondage($id);
    }


    /**
     * @OA\Get(
     *     path="/api/public/sondage/{id}",
     *     tags={"Public API"},
     *     summary="Récupérer les détails d'un sondage",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du sondage",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Détails du sondage récupérés avec succès"),
     *     @OA\Response(response="404", description="Sondage non trouvé"),
     * )
     */

    public function getSondage($id)
    {
        $sondageController = new SondageController();

        return $sondageController->getSondage($id);
    }



    //newsletter

    /**
     * @OA\Post(
     *     path="/api/public/newsletter",
     *     tags={"Public API"},
     *     summary="S'inscrire à la newsletter",
     *     @OA\RequestBody(
     *         description="Données à envoyer",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="example@gmail.com"),
     *        ),
     *    ),
     *    @OA\Response(response="200", description="Inscription à la newsletter effectuée avec succès"),
     *   @OA\Response(response="400", description="Email invalide"),
     * )
     */

    public function subscribeToNewsletter(Request $request)
    {
        $newsletterController = new \App\Http\Controllers\Api\NewsLetterController();

        return $newsletterController->addEmail($request);

    }


    /**
     * @OA\Delete(
     *     path="/api/public/newsletter",
     *     tags={"Public API"},
     *     summary="Se désinscrire de la newsletter",
     *     @OA\RequestBody(
     *         description="Données à envoyer",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="example@example.com"),
     *       ),
     *   ),
     *  @OA\Response(response="200", description="Désinscription de la newsletter effectuée avec succès"),
     * @OA\Response(response="400", description="Email invalide"),
     * )
     */


    public function unsubscribeToNewsletter(Request $request)
    {
        $newsletterController = new \App\Http\Controllers\Api\NewsLetterController();

        return $newsletterController->deleteEmail($request);
    }



    /**
     * @OA\Get(
     *     path="/api/public/sondages/type/{id}",
     *    tags={"Public API"},
     *    summary="Récupérer les sondages d'un type de sondage",
     *   @OA\Parameter(
     *        name="id",
     *      in="path",
     *     description="ID du type de sondage",
     *   required=true,
     *  @OA\Schema(type="integer")
     * ),
     * @OA\Response(response="200", description="Sondages récupérés avec succès"),
     * @OA\Response(response="404", description="Type de sondage non trouvé"),
     * )
     */

    public function getSondagesByTypeSondage($id)
    {
        $sondageController = new SondageController();

        return $sondageController->getByTypesSondages($id);
    }


    //by nom

    /**
     * @OA\Get(
     *     path="/api/public/sondages/type/nom/{nom}",
     *    tags={"Public API"},
     *    summary="Récupérer les sondages d'un type de sondage",
     *   @OA\Parameter(
     *        name="nom",
     *      in="path",
     *     description="Nom du type de sondage",
     *   required=true,
     *  @OA\Schema(type="string")
     * ),
     * @OA\Response(response="200", description="Sondages récupérés avec succès"),
     * @OA\Response(response="404", description="Type de sondage non trouvé"),
     * )
     */

    public function getSondagesByTypeSondageNom($nom)
    {
        $sondageController = new SondageController();

        return $sondageController->getByTypesSondagesNom($nom);
    }






}


