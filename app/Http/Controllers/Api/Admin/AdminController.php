<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Services\ElectionController;
use App\Http\Controllers\Api\Services\ElectionParticipantController;
use App\Http\Controllers\Api\Services\PartiPolitiqueController;
use App\Http\Controllers\Api\Services\ResultatsVotesController;
use App\Http\Controllers\Api\Services\SondageController;
use App\Http\Controllers\Api\Services\TypeSondageController;
use App\Http\Controllers\Controller;
use App\Models\Matricule;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function getAllUsers()
    {


        if (User::all()->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun utilisateur trouvé',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'list de tout les utilisateurs paginé par 10',
            'data' => User::paginate(10)
        ]);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Utilisateur trouvé',
            'data' => $user
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 400);
        }
        if ($user->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non supprimé',
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="not-defined-yet",
     *     tags={"Admin Actions"},
     *     summary="Add a new admin",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Admin")
     *     ),
     *     @OA\Response(response="201", description="Admin added successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */
    public function addAdmin(Request $request)
    {
    }


    /**
     * @OA\Post(
     *     path="/api/admin/add-election",
     *     tags={"Admin Actions"},
     *     summary="Add a new election",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Election")
     *     ),
     *     @OA\Response(response="201", description="Election added successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function addElection(Request $request)
    {
        $electionController = new ElectionController();

        return $electionController->addElection($request);
    }



    /**
     * @OA\Put(
     *     path="/api/admin/update-election/{id}",
     *     tags={"Admin Actions"},
     *     summary="Update an election",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Election")
     *     ),
     *     @OA\Response(response="201", description="Election updated successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function updateElection(Request $request, $id)
    {
        $electionController = new ElectionController();

        return $electionController->updateData($request, $id);
    }


    /**
     * @OA\Delete(
     *     path="/api/admin/delete-election/{id}",
     *     tags={"Admin Actions"},
     *     summary="Delete an election",
     *    security={{"bearerAuth":{}}},
     *     @OA\Response(response="201", description="Election deleted successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function deleteElection($id)
    {
        $electionController = new ElectionController();

        return $electionController->delete($id);
    }



    /**
     * @OA\Put(
     *     path="/api/admin/election/{id}/update-banner",
     *     tags={"Admin Actions"},
     *     summary="Update banner",
     *     security={{"bearerAuth":{}}},
     *    @OA\Parameter(
     *       name="id",
     *         description="ID de l'élection",
     *        required=true,
     *       in="path",
     *        @OA\Schema(
     *          type="integer",
     *     ),
     *   ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="banner", type="string", format="binary" , description=" Image Banniere de l'élection"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Banner updated successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */
    public function updateBanner(Request $request, $id)
    {
        $electionController = new ElectionController();

        return $electionController->updateBanner($id, $request);
    }




    /**
     * @OA\Put(
     *     path="/api/admin/election/{id}/update-logo",
     *     tags={"Admin Actions"},
     *     summary="Update banner",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *        name="id",
     *        description="ID de l'élection",
     *       required=true,
     *      in="path",
     *      @OA\Schema(
     *        type="integer",
     *    ),
     *  ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="logo", type="string", format="binary", description=" Image Logo de l'élection")
     *         )
     *     ),
     *     @OA\Response(response="201", description="logo updated successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */
    public function updateLogo(Request $request, $id)
    {
        $electionController = new ElectionController();

        return $electionController->updateImage($id, $request);
    }


    //sondage


    /**
     * @OA\Post(
     *     path="/api/admin/add-sondage",
     *     tags={"Admin Actions"},
     *     summary="Add a new sondage",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Sondage")
     *     ),
     *     @OA\Response(response="201", description="Sondage added successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */
    public function addSondage(Request $request)
    {
        $sondageController = new SondageController();

        return $sondageController->addSondage($request);
    }




    /**
     * @OA\Put(
     *     path="/api/admin/update-sondage/{id}",
     *     tags={"Admin Actions"},
     *     summary="update sondage",
     *    security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *        name="id",
     *          description="ID du sondage",
     *          required=true,
     *          in="path",
     *           @OA\Schema(
     *              type="integer",
     *          )
     *          ),
     *
     *    @OA\RequestBody(
     *        required=true,
     *       @OA\JsonContent(ref="#/components/schemas/Sondage")
     *   ),
     *     @OA\Response(response="201", description="Sondage added successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */


    public function updateSondage(Request $request, $id)
    {
        $sondageController = new SondageController();

        return $sondageController->updateSondage($id, $request);
    }


    /**
     * @OA\Delete(
     *    path="/api/admin/delete-sondage/{id}",
     *   tags={"Admin Actions"},
     *  summary="Delete sondage",
     *  security={{"bearerAuth":{}}},
     * @OA\Parameter(
     *        name="id",
     *          description="ID du sondage",
     *          required=true,
     *          in="path",
     *           @OA\Schema(
     *              type="integer",
     *          )
     *          ),
     * @OA\Response(response="201", description="Sondage deleted successfully"),
     * @OA\Response(response="400", description="Bad request")
     * )
     *
     */
    public function deleteSondage($id)
    {
        $sondageController = new SondageController();

        return $sondageController->deleteSondage($id);
    }


    //election participant



    //type sondage
    /**
     * @OA\Put(
     *     path="/api/admin/add-type-sondage",
     *     tags={"Admin Actions"},
     *     summary="Update a type sondage",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypeSondage")
     *     ),
     *     @OA\Response(response="201", description="Type sondage updated successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function addTypeSondage(Request $request)
    {
        $typeSondageController = new TypeSondageController();

        return $typeSondageController->addTypeSondage($request);
    }


    /**
     * @OA\Put(
     *     path="/api/admin/update-type-sondage/{id}",
     *     tags={"Admin Actions"},
     *     summary="Update a type sondage",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypeSondage")
     *     ),
     *     @OA\Response(response="201", description="Type sondage updated successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function updateTypeSondage(Request $request, $id)
    {
        $typeSondageController = new TypeSondageController();

        return $typeSondageController->updateTypeSondage($request, $id);
    }


    /**
     * @OA\Delete(
     *     path="/api/admin/delete-type-sondage/{id}",
     *     tags={"Admin Actions"},
     *     summary="Delete a type sondage",
     *    security={{"bearerAuth":{}}},
     *     @OA\Response(response="201", description="Type sondage deleted successfully"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function deleteTypeSondage($id)
    {
        $typeSondageController = new TypeSondageController();

        return $typeSondageController->deleteTypeSondage($id);
    }

    /**
     * @OA\Post(
     *     path="api/admin/elections/add-participant",
     *     tags={"Admin Actions"},
     *    description="Ajouter un participant à une élection",
     *
     *
     *     summary="Ajouter un participant à une élection",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ElectionParticipant")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Succès - Participant ajouté avec succès"),
     *     @OA\Response(response="401", description="Non autorisé - L'utilisateur n'est pas authentifié"),
     * )
     */

    public function addElectionParticipant(Request $request){
        $partcipantController = new ElectionParticipantController();

        return $partcipantController->addPartcicipant($request);
    }



    //delete participant

    /**
     * @OA\Delete(
     *     path="/api/admin/elections/delete-participant",
     *     tags={"Admin Actions"},
     *    description="Supprimer un participant à une élection",
     *
     *     summary="Supprimer un participant à une élection",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ElectionParticipant")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Succès - Participant supprimé avec succès"),
     *     @OA\Response(response="401", description="Non autorisé - L'utilisateur n'est pas authentifié"),
     * )
     */

    public function deleteElectionParticipant(Request $request){
        $partcipantController = new ElectionParticipantController();

        return $partcipantController->deletePartcipant($request);
    }


    //calculer les resultats

    /**
     * @OA\Get(
     *     path="/api/admin/elections/{id}/calculate-result",
     *     tags={"Admin Actions"},
     *     summary="Calculer les résultats d'une élection",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'élection",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response="200", description="Succès - Résultats calculés et renvoyés"),
     *     @OA\Response(response="401", description="Non autorisé - L'utilisateur n'est pas authentifié"),
     * )
     */
    public function calculateResult($id){
        $partcipantController = new ResultatsVotesController();

        return $partcipantController->calculerResultats($id);
    }


    //get resultats
    /**
     * @OA\Get(
     *     path="/api/admin/elections/{id}/get-result",
     *     tags={"Admin Actions"},
     *     summary="Obtenir les résultats d'une élection",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'élection",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response="200", description="Succès - Résultats de l'élection renvoyés"),
     *     @OA\Response(response="401", description="Non autorisé - L'utilisateur n'est pas authentifié"),
     * )
     */

    public function getResultats(int $id){
        $partcipantController = new ResultatsVotesController();

        return $partcipantController->getResultats($id);
    }



    /**
     * @OA\Post(
     *     path="/not-defined-yet",
     *     tags={"Admin Actions"},
     *     summary="Ajouter un candidat",
     *     @OA\Parameter(
     *         name="id_user",
     *         in="path",
     *         description="ID de l'utilisateur à enregistrer comme candidat",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id_parti_politique",
     *         in="path",
     *         description="ID du parti politique auquel le candidat appartient",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Candidat ajouté avec succès"),
     *     @OA\Response(response="404", description="Utilisateur non trouvé")
     * )
     */


    public function addCandidat(int $id_user, int $id_parti_politique){

        $user = User::find($id_user);
        $user->role_id = 2;
        $user->save();

        $candidat = \App\Models\Candidat::create([
            'user_id' => $user->id,
            'pt_id' => $id_parti_politique
        ]);

        return response()->json([
            'candidat' => $candidat,
            'user' => $user
        ]);

    }


    public function generateMatricule($commune)
    {
        $matricule = "CA";
        $matricule .= rand(10000000, 99999999);
        $matricule .= strtoupper(substr($commune, 0, 1));
        //verifier que ca n'existe pas dans password user
        //verifier si le matricule existe deja dans la base de donnée
        $mat =  Matricule::where('matricule', $matricule)->first();
        if ($mat) {
            $this->generateMatricule($commune);
        }

        return $matricule;
    }


    /**
     * @OA\Post(
     *     path="/api/admin/add-candidat",
     *    tags={"Admin Actions"},
     *    summary="Ajouter un candidat",
     *   security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *       required=true,
     *     @OA\JsonContent(ref="#/components/schemas/CandidatRequest")
     *  ),
     *    @OA\Response(response="201", description="Candidat ajouté avec succès"),
     *   @OA\Response(response="400", description="Bad request")
     * )
     * )
     *
     */


    public function add(Request $addCandidatRequest)
    {

        $addCandidatRequest->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'commune' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'pt_id' => 'required|integer|exists:parti_politiques,id',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);


        $password = $this->generateMatricule($addCandidatRequest["commune"]);

        $userData = [
            'nom' => $addCandidatRequest["nom"],
            'prenom' => $addCandidatRequest["prenom"],
            'commune' => $addCandidatRequest["commune"],
            'email' => $addCandidatRequest["email"],
            'phone' => $addCandidatRequest["phone"],
            'role_id' => 2,
            'password' => bcrypt($password)
        ];


        $user = User::where('email', $userData["email"])->orWhere('phone', $userData["phone"])->first();

        if ($user) {
            return response()->json([
                'message' => "Cet email ou ce numéro de téléphone existe déjà",

            ], 400);
        }
        if ($addCandidatRequest->hasFile('photo_url')) {
            $file = $addCandidatRequest['photo_url'];
            //recuperer le nom du fichier
            $fileName = $file->getClientOriginalName();
            //recuperer l'extension du fichier
            $extension = $file->getClientOriginalExtension();

            //generer un nom unique pour le fichier
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

            //deplacer le fichier vers le dossier de stockage

            $file->move('storage/photos', $fileNameToStore);

            //enregistrer le nom du fichier dans la base de donnees
            $nameToFront = 'storage/photos/' . $fileNameToStore;

            $addCandidatRequest['photo_url'] = $nameToFront;
        }

        $newuser = User::create($userData);


        $candidat = $this->addCandidat($newuser->id, $addCandidatRequest->pt_id);


        Matricule::create([
            'matricule' => $password,
        ]);

        //envoyer un mail au candidat pour lui donner son mot de passe

        $to = $userData["email"];
        $subject = "Bienvenue sur la plateforme des candidats";
        $message = "Bonjour, <br> Votre code candidat est : <b>" . $password . "</b> <br> Merci de vous connecter sur la plateforme avec ce code et votre email pour accéder à votre compte";
        $headers = "From:mavoix.com \r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html; charset=UTF-8\r\n";
        if (mail($to, $subject, $message, $headers)) {
            return response()->json([
                'success' => true,
                'message' => 'Candidat ajouté avec succès',
                'data' => [
                    'user_id' => $newuser,
                    'candidat' => $candidat,
                ]
            ], 201);

        } else {
            return response()->json([
                'success' => true,
                'candidat' => $candidat,
                'user' => $newuser,
                'message' => 'Candidat ajouté avec succès',
                'mail' => 'Mail non envoyé',
            ], 201);
        }
    }






    //update candidat

    /**
     * @OA\Put(
     *     path="/api/admin/update-candidat/{id_user}/{id_parti_politique}",
     *     tags={"Admin Actions"},
     *     summary="Mettre à jour un candidat",
     *     @OA\Parameter(
     *         name="id_user",
     *         in="path",
     *         description="ID de l'utilisateur à mettre à jour comme candidat",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id_parti_politique",
     *         in="path",
     *         description="ID du nouveau parti politique auquel le candidat appartient",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Candidat modifié avec succès"),
     *     @OA\Response(response="404", description="Utilisateur non trouvé")
     * )
     */

    public function updateCandidat(int $id_user, int $id_parti_politique){

        $user = User::find($id_user);
        $user->role_id = 2;
        $user->save();

        $candidat = \App\Models\Candidat::where('user_id', $user->id)->update([
            'pt_id' => $id_parti_politique
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Candidat modifié avec succès',
            'candidat' => $candidat,
            'user' => $user
        ]);

    }


    //delete candidat


    /**
     * @OA\Delete(
     *     path="/api/admin/delete-candidat/{id_user}",
     *     tags={"Admin Actions"},
     *     summary="Supprimer un candidat",
     *     @OA\Parameter(
     *         name="id_user",
     *         in="path",
     *         description="ID de l'utilisateur à supprimer comme candidat",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Candidat supprimé avec succès"),
     *     @OA\Response(response="404", description="Utilisateur non trouvé")
     * )
     */

    public function deleteCandidat(int $id_user){
        $user = User::find($id_user);
        $user->role_id = 1;
        $user->save();

        $candidat = \App\Models\Candidat::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Candidat supprimé avec succès',
            'candidat' => $candidat,
            'user' => $user
        ]);

    }




    //parti politique

    /**
     * @OA\Post(
     *     path="/api/admin/add-parti-politique",
     *     tags={"Admin Actions"},
     *     summary="Ajouter un nouveau parti politique",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Politique")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Parti politique ajouté avec succès"),
     *     @OA\Response(response="422", description="Validation error"),
     * )
     */
    public function addPartiPolitique(Request $request){
        $partiPolitiqueController = new PartiPolitiqueController();

        return $partiPolitiqueController->addPartiPolitique($request);
    }


    /**
     * @OA\Put(
     *     path="/api/admin/parti-politique/{id}",
     *     tags={"Admin Actions"},
     *     summary="Mettre à jour un parti politique",
     *     security={{"bearerAuth":{}}},
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Politique")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Parti politique mis à jour avec succès"),
     *     @OA\Response(response="404", description="Parti politique non trouvé"),
     * )
     */
    public function updatePartiPolitique(Request $request, $id){
        $partiPolitiqueController = new PartiPolitiqueController();

        return $partiPolitiqueController->updatePartiPolitique($request, $id);
    }


    /**
     * @OA\Delete(
     *     path="/api/admin/parti-politique/{id}",
     *     tags={"Admin Actions"},
     *     summary="Supprimer un parti politique",
     *     security={{"bearerAuth":{}}},
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
     *     @OA\Response(response="200", description="Parti politique supprimé avec succès"),
     *     @OA\Response(response="404", description="Parti politique non trouvé"),
     * )
     */

    public function deletePartiPolitique($id){
        $partiPolitiqueController = new PartiPolitiqueController();

        return $partiPolitiqueController->deletePartiPolitique($id);
    }


    //get all sondages


    public function getAllSondages(){
        $sondageController = new SondageController();

        return $sondageController->getAllSondages();
    }
}
