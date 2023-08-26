<?php

namespace App\Http\Controllers\Api\PrivateApi;

use App\Http\Controllers\Api\Services\CommentaireController;
use App\Http\Controllers\Api\Services\CommentaireRepliqueController;
use App\Http\Controllers\Api\Services\FollowerController;
use App\Http\Controllers\Api\Services\LikeController;
use App\Http\Controllers\Api\Services\MeetParticipantController;
use App\Http\Controllers\Api\Services\ResultatSondageController;
use App\Http\Controllers\Api\Services\SondageController;
use App\Http\Controllers\Api\Services\VotesController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{


    /**
     * @OA\Post(
     *     path="/api/private/user/add-photo",
     *     tags={"User Authenticated actions"},
     *     summary="Ajouter une photo de profil",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            @OA\Property(property="photo", type="string", format="binary"))
     *     ),
     *     @OA\Response(response="200", description="Photo ajoutee avec succes"),
     *     @OA\Response(response="404", description="Utilisateur non trouve")
     * )
     */

    public function addPhoto(Request $request)
    {
        $request->validate([
            'photo_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|string'
        ]);
        $id = auth()->user()->id;
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        //verifier si c'est un string
        if (is_string($request->photo_url) && $request->photo_url != null) {
            $user->photo_url = $request->photo_url;
            $user->save();
            return response()->json([
                'success' => 'Photo added successfully'
            ], 200);
        }


        $file = $request->file('photo_url');
        if (!$file) {
            return response()->json([
                'message' => 'Veuillez choisir une photo'
            ], 404);
        }
        if ($user->photo_url) {
            Storage::delete($user->photo_url);
        }
        //recuperer le nom du fichier
        $fileName = $file->getClientOriginalName();
        //recuperer l'extension du fichier
        $extension = $file->getClientOriginalExtension();

        //generer un nom unique pour le fichier
        $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

        //deplacer le fichier vers le dossier de stockage

        $file->move('storage/photos', $fileNameToStore);
        $NameToFront = 'storage/photos/' . $fileNameToStore;

        //enregistrer le nom du fichier dans la base de donnees
        $user->photo_url = $NameToFront;
        $user->save();


        return response()->json([
            'success' => 'Photo added successfully'
        ], 200);
    }


    /**
     * @OA\Put(
     *     path="/api/private/user/update-datas",
     *     tags={"User Authenticated actions"},
     *     summary="Modifier les donnees d'un utilisateur",
     *
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response="200", description="Donnees modifiees avec succes"),
     *     @OA\Response(response="404", description="Utilisateur non trouve")
     * )
     */

    public function updateDatas(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'elector_card' => 'required|string',
            'adresse' => 'required|string',
            'numero_cni' => 'required|string',
            'commune' => 'required|string',
            'role_id' => 'required|integer',
            'phone' => 'required|string',
            'email' => 'required|string'
        ]);

        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $user->elector_card = $request->input('elector_card');
        $user->adresse = $request->input('adresse');
        $user->numero_cni = $request->input('numero_cni');
        $user->commune = $request->input('commune');
        $user->role_id = $request->input('role_id');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');

        $user->save();

        return response()->json([
            'success' => 'Utilisateur modifie avec succes'
        ], 200);
    }


    /**
     * @OA\Put(
     *     path="/api/private/user/update-password",
     *     tags={"User Authenticated actions"},
     *     summary="Modifier le mot de passe d'un utilisateur",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Mot de passe modifié avec succès"),
     *     @OA\Response(response="404", description="Utilisateur non trouvé")
     * )
     */


    public function updatePassword(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouve'
            ], 404);
        }

        $request->validate([
            'password' => 'required|string'
        ]);

        $user->password = bcrypt($request->input('password'));

        $user->save();

        return response()->json([
            'success' => 'Mot de passe modifie avec succes'
        ], 200);
    }



    /**
     * @OA\Post(
     *     path="/api/public/user/reset-password",
     *     tags={"Public API"},
     *     summary="Reinitialiser le mot de passe d'un utilisateur",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *           @OA\Property(property="phone", type="string"),
     *           @OA\Property(property="nom", type="string"),
     *           @OA\Property(property="email", type="string"),
     *           @OA\Property(property="password", type="string")
     *     ),
     *    ),
     *
     *     @OA\Response(response="200", description="Mot de passe reinitialise avec succes"),
     *     @OA\Response(response="404", description="Utilisateur non trouve")
     * )
     */
    public function resetPassword(Request $request){
        $request->validate([
            'phone' => 'required|string',
            'nom' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('phone', $request->input('phone'))
            ->where('nom', $request->input('nom'))
            ->where('email', $request->input('email'))
            ->first();
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouve'
            ], 404);
        }

        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response()->json([
            'success' => 'Mot de passe reinitialise avec succes',
            'password' => $request->input('password')
        ], 200);
    }



    //getUserDatas

    /**
     * @OA\Get(
     *     path="/api/private/user/get-my-datas",
     *     tags={"User Authenticated actions"},
     *     summary="Recuperer les donnees d'un utilisateur",
     *     @OA\Response(response="200", description="Donnees recuperees avec succes"),
     *     @OA\Response(response="404", description="Utilisateur non trouve")
     * )
     */
    public function getUserDatas(){
        $id = auth()->user()->id;
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouve'
            ], 404);
        }

        return response()->json([
            'success' => 'Donnees recuperees avec succes',
            'data' => $user
        ], 200);
    }




    //comments

    /**
     * @OA\Post(
     *     path="/api/private/user/add-comment",
     *     tags={"User Authenticated actions"},
     *     summary="Add a new comment",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Commentaire")
     *     ),
     *     @OA\Response(response="201", description="Comment added successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function addComments(Request $request){
        $commentaireController = new CommentaireController();

        return $commentaireController->addCommentaire($request);
    }





    public function getComments(int $id){
        $commentaireController = new CommentaireController();

        return $commentaireController->getPostCommentaires($id);
    }

    /**
     * @OA\Delete(
     *     path="/api/private/user/{id}/delete-comment",
     *     tags={"User Authenticated actions"},
     *     summary="Supprimer un commentaire",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du commentaire",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Commentaire supprime avec succes"),
     *     @OA\Response(response="404", description="Commentaire non trouve")
     * )
     */

    public function deleteComment(int $id){
        $commentaireController = new CommentaireController();

        return $commentaireController->deleteCommentaire($id);
    }



    /**
     * @OA\Get(
     *     path="/api/private/user/my-comments",
     *     tags={"User Authenticated actions"},
     *     summary="Recuperer les commentaires d'un utilisateur",
     *     @OA\Response(response="200", description="Commentaires recuperes avec succes"),
     *     @OA\Response(response="404", description="Commentaires non trouves")
     * )
     */
    public function myComment(){
        $commentaireController = new CommentaireController();

        return $commentaireController->getUserCommentaires(auth()->user()->id);
    }


    /**
     * @OA\Put(
     *     path="not-defined-yet",
     *     tags={"User Authenticated actions"},
     *     summary="Modifier un commentaire",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du commentaire",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Commentaire")
     *     ),
     *     @OA\Response(response="200", description="Commentaire modifie avec succes"),
     *     @OA\Response(response="404", description="Commentaire non trouve")
     * )
     */

    public function updateComment(int $id, Request $request){
        $commentaireController = new CommentaireController();

        return $commentaireController->updateCommentaire($id, $request);
    }


    /**
     * @OA\Delete(
     *     path="/api/private/user/{id}/{id_user}/delete-comment",
     *     tags={"User Authenticated actions"},
     *     summary="Supprimer un commentaire",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du commentaire",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="id_user",
     *         in="path",
     *         description="Id de l'utilisateur",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(response="200", description="Commentaire supprime avec succes"),
     *     @OA\Response(response="404", description="Commentaire non trouve")
     * )
     */

    public function deleteByUserAndId(int $id, int $id_user){
        $commentaireController = new CommentaireController();

        return $commentaireController->deleteCommentaireByUserAndId($id, $id_user);
    }


    //commentaire response

    /**
     * @OA\Post(
     *     path="/api/private/user/add-comment-response",
     *     tags={"User Authenticated actions"},
     *     summary="Add a new comment response",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CommentaireReplique")
     *     ),
     *     @OA\Response(response="201", description="Comment response added successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function addCommentResponse(Request $request){
        $commentaireRepliqueController = new CommentaireRepliqueController();

        return $commentaireRepliqueController->addCommentaireReplique($request);
    }





    /**
     * @OA\Delete(
     *     path="/api/private/user/{id}/delete-comment-response",
     *     tags={"User Authenticated actions"},
     *     summary="Supprimer un commentaire",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du commentaire response",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Commentaire supprime avec succes"),
     *     @OA\Response(response="404", description="Commentaire non trouve")
     * )
     */
    public function deleteCommentResponse(int $id){
        $commentaireRepliqueController = new CommentaireRepliqueController();

        return $commentaireRepliqueController->deleteCommentaireReplique($id);
    }



    /**
     * @OA\Get(
     *     path="/api/private/user/my-comment-responses",
     *     tags={"User Authenticated actions"},
     *     summary="Recuperer les commentaires d'un utilisateur",
     *     @OA\Response(response="200", description="Commentaires recuperes avec succes"),
     *     @OA\Response(response="404", description="Commentaires non trouves")
     * )
     */
    public function myCommentResponse(){
        $commentaireRepliqueController = new CommentaireRepliqueController();

        return $commentaireRepliqueController->getCommentaireReplique(auth()->user()->id);
    }


    //Likes



    /**
     * @OA\Post(
     *     path="/api/private/user/add-like",
     *     tags={"User Authenticated actions"},
     *     summary="Add a new like",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Like")
     *     ),
     *     @OA\Response(response="201", description="Like added successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function addLike(Request $request){
        $likeController = new LikeController();

        return $likeController->addLike($request);
    }




    /**
     * @OA\Delete(
     *     path="/api/private/user/{id}/delete-like",
     *     tags={"User Authenticated actions"},
     *     summary="Supprimer un like",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du like",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(response="200", description="Like supprime avec succes"),
     *     @OA\Response(response="404", description="Like non trouve")
     * )
     */

    public function deleteLike(int $id){
        $likeController = new LikeController();

        return $likeController->deleteLike($id);
    }




    /**
     * @OA\Get(
     *     path="/api/private/user/my-likes",
     *     tags={"User Authenticated actions"},
     *     summary="Recuperer les likes d'un utilisateur",
     *     @OA\Response(response="200", description="Likes recuperes avec succes"),
     *     @OA\Response(response="404", description="Likes non trouves")
     * )
     */
    public function myLikes(){
        $likeController = new LikeController();

        return $likeController->getUserLikes(auth()->user()->id);
    }



    /**
     * @OA\Delete(
     *     path="/api/private/user/{id}/{id_user}/delete-like",
     *     tags={"User Authenticated actions"},
     *     summary="Supprimer un like",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *
     *     ),
     *     @OA\Parameter(
     *         name="id_user",
     *         in="path",
     *         description="Id de l'utilisateur",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *
     *     ),
     *
     *     @OA\Response(response="200", description="Like supprime avec succes"),
     *     @OA\Response(response="404", description="Like non trouve")
     * )
     */


    public function deleteByUserAndPostId(int $id, int $id_user){
        $likeController = new LikeController();

        return $likeController->deleteLikeByUserAndPost($id_user, $id);
    }


    //Meet

    /**
     * @OA\Post(
     *     path="/api/private/user/join-meet",
     *     tags={"User Authenticated actions"},
     *     summary="Rejoindre une reunion",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MeetParticipant")
     *     ),
     *     @OA\Response(response="201", description="Reunion rejointe avec succes"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function joinMeet(Request $request){
        $meetController = new MeetParticipantController();

        return $meetController->joinMeet($request);
    }


    /**
     * @OA\Post(
     *     path="/api/private/user/quit-meet",
     *     tags={"User Authenticated actions"},
     *     summary="Quitter une reunion",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MeetParticipant")
     *     ),
     *     @OA\Response(response="201", description="Reunion quittee avec succes"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function quitMeet(Request $request){
        $meetController = new MeetParticipantController();

        return $meetController->quitMeet($request);
    }


    //sondage

    /**
     * @OA\Post(
     *     path="/api/private/user/add-vote",
     *     tags={"User Authenticated actions"},
     *     summary="Ajouter un vote",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ResultatSondage")
     *     ),
     *     @OA\Response(response="201", description="Vote ajoute avec succes"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */
    public function vote(Request $request){
        $resultatSondageController = new ResultatSondageController();

        return $resultatSondageController->addMyVote($request);
    }


    /**
     * @OA\Get(
     *     path="/api/private/user/{id_sondage}/get-my-vote",
     *     tags={"User Authenticated actions"},
     *     summary="Recuperer mon vote",
     *     @OA\Parameter(
     *         name="id_sondage",
     *         in="path",
     *         description="Id du sondage",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *
     *     ),
     *
     *     @OA\Response(response="200", description="Vote recupere avec succes"),
     *     @OA\Response(response="404", description="Vote non trouve")
     * )
     */

    public function getMyVote(int $id_sondage){
        $resultatSondageController = new ResultatSondageController();

        return $resultatSondageController->getMyVote($id_sondage, auth()->user()->id);
    }



    /**
     * @OA\Get(
     *     path="/api/private/user/get-sondages",
     *     tags={"User Authenticated actions"},
     *     summary="Recuperer les sondages auxquels l'utilisateur n'a pas encore vote",
     *     @OA\Response(response="200", description="Sondages recuperes avec succes"),
     *     @OA\Response(response="404", description="Sondages non trouves")
     * )
     */

    public function getNotVotedSondages(){
        $resultatSondageController = new SondageController();

        return $resultatSondageController->getSondagesNotVotedByUser();

    }

    public function getVotes(int $id){
        $resultatSondageController = new ResultatSondageController();

        return $resultatSondageController->getVotes($id);
    }


    /**
     * @OA\Put(
     *     path="/api/private/user/{id_sondage}/update-my-vote",
     *     tags={"User Authenticated actions"},
     *     summary="Modifier mon vote",
     *     @OA\Parameter(
     *         name="id_sondage",
     *         in="path",
     *         description="Id du sondage",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ResultatSondage")
     *     ),
     *     @OA\Response(response="200", description="Vote modifie avec succes"),
     *     @OA\Response(response="404", description="Vote non trouve")
     * )
     */
    public function updateMyVote(Request $request, int $id_sondage){
        $resultatSondageController = new ResultatSondageController();

        return $resultatSondageController->updateMyVote($request);
    }



    /**
     * @OA\Delete(
     *     path="/api/private/user/{id_sondage}/delete-my-vote",
     *     tags={"User Authenticated actions"},
     *     summary="Supprimer mon vote",
     *     @OA\Parameter(
     *         name="id_sondage",
     *         in="path",
     *         description="Id du sondage",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *
     *     ),
     *
     *     @OA\Response(response="200", description="Vote supprime avec succes"),
     *     @OA\Response(response="404", description="Vote non trouve")
     * )
     */
    public function deleteMyVote(int $id_sondage){
        $resultatSondageController = new ResultatSondageController();

        return $resultatSondageController->deleteMyVote($id_sondage, auth()->user()->id);
    }


    //vote


    /**
     * @OA\Post(
     *     path="/api/private/vote/add-election-vote",
     *     tags={"User Authenticated actions"},
     *     summary="Ajouter un vote",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Vote")
     *     ),
     *     @OA\Response(response="201", description="Vote ajoute avec succes"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */
    public function addVote(Request $request){
        $voteController = new VotesController();

        return $voteController->addVote($request);
    }




    /**
     * @OA\Get(
     *     path="/api/private/user/{id_election}/{id_user}/get-my-vote",
     *     tags={"User Authenticated actions"},
     *     summary="Recuperer un vote",
     *     @OA\Parameter(
     *         name="id_election",
     *         in="path",
     *         description="Id de l'election",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *    ),
     *    @OA\Parameter(
     *        name="id_user",
     *       in="path",
     *      description="Id de l'utilisateur",
     *    required=true,
     *   @OA\Schema(
     *     type="integer",
     *    format="int64"
     *  ),
     * ),
     *    @OA\Response(response="200", description="Vote recupere avec succes"),
     *   @OA\Response(response="404", description="Vote non trouve")
     * )
     *
     *
     */

    public function getVoteByUserAndElection(int $id_election, int $id_user){
        $voteController = new VotesController();
        $request = new Request();
        $request->merge([
            'id_election' => $id_election,
            'id_user' => $id_user
        ]);

        return $voteController->getVoteByUserAndElection($request);
    }


   /**
    * @OA\Delete(
    *     path="not-defined-yet",
    *     tags={"User Authenticated actions"},
    *     summary="Supprimer un vote",
    *     @OA\Parameter(
    *         name="id_election",
    *         in="path",
    *         description="Id de l'election",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="id_user",
    *         in="path",
    *         description="Id de l'utilisateur",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="200", description="Vote supprime avec succes"),
    *     @OA\Response(response="404", description="Vote non trouve")
    * )
    */
    public function deleteVote(int $id_election, int $id_user){
        $voteController = new VotesController();
        $request = new Request();
        $request->merge([
            'id_election' => $id_election,
            'id_user' => $id_user
        ]);

        return $voteController->deleteVote($request);
    }



    //follower section

    /**
     * @OA\Post(
     *     path="/api/private/user/follow-candidat",
     *     tags={"User Authenticated actions"},
     *     summary="Ajouter un follower",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Follower")
     *     ),
     *     @OA\Response(response="201", description="Follower ajoute avec succes"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function follow(Request $request){
        $followerController = new FollowerController();

        return $followerController->follow($request);

    }


    //get-following

    /**
     * @OA\Get(
     *     path="/api/private/user/get-following",
     *     tags={"User Authenticated actions"},
     *     summary="Recuperer les candidats suivis par un utilisateur",
     *     @OA\Response(response="200", description="Followers recuperes avec succes"),
     *     @OA\Response(response="404", description="Followers non trouves")
     * )
     */

    public function getFollowing(){
        $followerController = new FollowerController();

        return $followerController->showFollowings();

    }


    //is-following


    /**
     * @OA\Get(
     *     path="/api/private/user/{id}/is-following",
     *     tags={"User Authenticated actions"},
     *     summary="Voir si un utilisateur suit un candidat précis",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du candidat",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *    ),
     *    @OA\Response(response="200", description="Followers recuperes avec succes"),
     *    @OA\Response(response="404", description="Followers non trouves")
     * )
     */

    public function isFollowing(int $id){
        $followerController = new FollowerController();

        return $followerController->isFollowing($id);

    }

    //unfollow

    /**
     * @OA\Delete(
     *     path="/api/private/user/unfollow",
     *    tags={"User Authenticated actions"},
     *    summary="Supprimer un follower",
     *   @OA\RequestBody(
     *        required=true,
     *      @OA\JsonContent(ref="#/components/schemas/Follower"),
     *  ),
     * @OA\Response(response="200", description="Follower supprime avec succes"),
     * @OA\Response(response="404", description="Follower non trouve")
     * )
     *
     */

     public function unfollow(Request $request){
         $followerController = new FollowerController();

         return $followerController->unfollow($request);
    }

    /**
     * @OA\Get(
     *     path="/api/private/user/{id}/get-followers",
     *     tags={"User Authenticated actions"},
     *     summary="Recuperer les followers d'un candidat",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id du candidat",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *    ),
     *    @OA\Response(response="200", description="Followers recuperes avec succes"),
     *    @OA\Response(response="404", description="Followers non trouves")
     * )
     */

    public function getFollowers(int $id){
        $followerController = new FollowerController();

        return $followerController->showFollowers($id);

    }





}
