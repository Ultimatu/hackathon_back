<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PrivateApi\UserController;
use App\Http\Controllers\Api\PrivateApi\CandidatController;
use App\Http\Controllers\Api\PublicApi\PublicController;
use App\Http\Controllers\Api\ResultatSondageController;
use App\Http\Controllers\Api\ResultatsVotesController;
use App\Http\Controllers\Api\VotesController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



//authentification
Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'
    ])->name('login')->middleware("guest");
    Route::post('/register',
        [AuthController::class, 'register']
    )->name('register')->middleware("guest");
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware("auth");
});


/*
API ACCESSIBLE AUX PUBLIC A LA PAGE D'ACCUEIL
*/

Route::group(['prefix' => 'public'], function () {
    // Activities
    Route::get('activities', [PublicController::class, 'getAllActvities']);
    Route::get('activity/{id}', [PublicController::class, 'getActivityDatas']);
    Route::get('candidat/{id}/get-activities', [PublicController::class, 'getCandidatsActivities']);



    // Comments
    Route::get('post/{id}/comments', [PublicController::class, 'getPostComments']);
    Route::get('comment/{id}/replies', [PublicController::class, 'getCommentsReplique']);
    Route::get('comment/{id}/all-replies', [PublicController::class, 'getCommentAllRepliques']);

    // Elections
    Route::get('elections', [PublicController::class, 'getAllElections']);
    Route::get('election/{id}', [PublicController::class, 'getElectionData']);
    Route::get('election/{id}/participants', [PublicController::class, 'getElectionParticipants']);

    // Likes
    Route::get('post/{id}/likes', [PublicController::class, 'getAllPostLikes']);

    // Meets
    Route::get('meets/{id_user}', [PublicController::class, 'getMeets']);

    // Sondages
    Route::get('sondages', [PublicController::class, 'getAllSondages']);
    Route::get('sondages/{commune}', [PublicController::class, 'getAllSondageByCommune']);


    //parti politique
    Route::get('parti-politiques', [PublicController::class, 'getAllPartiPolitiques']);

    Route::get('/parti-politique/{id}', [PublicController::class, 'getPartiPolitique']);

    Route::post('user/reset-password', [UserController::class, 'resetPassword']);

    //candidats
    Route::get('candidats', [PublicController::class, 'getAllCandidats']);

    //get candidat by id
    Route::get('candidat/{id}', [PublicController::class, 'getCandidatById']);

    //get by nom
    Route::get('candidats/search/{val}', [PublicController::class, 'searchCandidats']);


    //newsletters
    Route::post('newsletter', [PublicController::class, 'subscribeToNewsletter']);

    Route::delete('newsletter/{id}', [PublicController::class, 'unsubscribeToNewsletter']);

    //get type sondage
    Route::get('types-sondages', [PublicController::class, 'getAllTypesSondages']);

    //get sondage by id
    Route::get('type-sondage/{id}', [PublicController::class, 'getTypeSondageById']);

    //get sondage by id

    Route::get('sondage/{id}', [PublicController::class, 'getSondageById']);

    //get sondage by type sondage
    Route::get('sondages/type/{id}', [PublicController::class, 'getSondagesByTypeSondage']);

    //get sondage by type sondage nom
    Route::get('sondages/type/nom/{nom}', [PublicController::class, 'getSondagesByTypeSondageNom']);



    Route::get('posts', [PublicController::class, 'getAllPosts'])->name('all-posts');

    Route::get('posts/{id}', [PublicController::class, 'getPostById'])->name('post-by-id');

    Route::get('posts/candidat/{id}/posts', [PublicController::class, 'getCandidatPosts'])->name('posts-by-candidat');

    //search posts
    Route::get('posts/search/{val}', [PublicController::class, 'searchPosts'])->name('search-posts');


});

/*
 USER , ADMIN, CANDIDAT Authenticated api's
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'private'], function () {
        // Photos
        Route::post('user/add-photo', [UserController::class, 'addPhoto']);

        // Data
        Route::put('user/update-datas', [UserController::class, 'updateDatas']);

        // Password
        Route::put('user/update-password', [UserController::class, 'updatePassword']);


        // Comments
        Route::post('user/add-comment', [UserController::class, 'addComments']);
        Route::delete('user/{id}/delete-comment', [UserController::class, 'deleteComment']);
        Route::delete('user/{id}/{id_user}', [UserController::class, 'deleteByUserAndId']);
        Route::get('user/my-comments', [UserController::class, 'myComment']);

        // Comment Responses
        Route::post('user/add-comment-responses', [UserController::class, 'addCommentResponse']);
        Route::delete('user/{id}/delete-comment-response', [UserController::class, 'deleteCommentResponse']);
        Route::get('user/my-comment-responses', [UserController::class, 'myCommentResponse']);

        // Likes
        Route::post('user/add-like', [UserController::class, 'addLike']);
        Route::delete('user/{id}/delete-like', [UserController::class, 'deleteLike']);
        Route::get('user/my-likes', [UserController::class, 'myLikes']);

        Route::delete('user/{id}/{id_user}/delete-like', [UserController::class, 'deleteByUserAndPostId']);

        // Meet
        Route::post('user/join-meet', [UserController::class, 'joinMeet']);
        Route::post('user/quit-meet', [UserController::class, 'quitMeet']);


        // Sondage

        Route::post('user/add-vote', [UserController::class, 'vote']);
        Route::get('user/{id_sondage}/get-my-vote', [UserController::class, 'getMyVote']);

        Route::put('user/{id}/update-my-vote', [UserController::class, 'updateMyVote']);

        Route::delete('user/{id}/delete-my-vote', [UserController::class, 'deleteMyVote']);

        Route::get('user/get-sondages', [UserController::class, 'getNotVotedSondages']);


        // Election Votes

        Route::post('user/vote/add-election-vote', [UserController::class, 'addVote']);

        Route::get('user/{id_election}/{id_user}/get-my-vote', [UserController::class, 'getVoteByUserAndElection']);


        //follow

        Route::post('user/follow-candidat', [UserController::class, 'follow']);

        Route::delete('user/unfollow-candidat', [UserController::class, 'unfollow']);

        Route::get('user/{id}/get-followers', [UserController::class, 'getFollowers']);

        Route::get('user/get-following', [UserController::class, 'getFollowing']);





    });
});


/*
  CANDIDAT Authenticated api's
*/

Route::middleware(['auth:sanctum', 'candidat'])->group(function () {

    Route::group(['prefix' => 'private'], function () {
        // Posts
        Route::post('candidat/add-post', [CandidatController::class, 'addPost']);
        Route::get('candidat/{id}/get-posts', [CandidatController::class, 'getPosts']);
        Route::delete('candidat/{id}/delete-post', [CandidatController::class, 'deletePost']);

        // Meets
        Route::post('candidat/add-meet', [CandidatController::class, 'addMeet']);
        Route::get('candidat/{id}/get-meets', [CandidatController::class, 'getMeets']);
        Route::get('candidat/search-meets/{val}', [CandidatController::class, 'searchMeets']);
        Route::delete('candidat/{id}/delete-meet', [CandidatController::class, 'deleteMeet']);

        // Activities

        Route::post('candidat/add-activity', [CandidatController::class, 'addActivity']);
        Route::put('candidat/update-activity/{id}', [CandidatController::class, 'updateActivity']);
        Route::delete('candidat/{id}/delete-activity', [CandidatController::class, 'deleteActivity']);

        //followers

        Route::get('/get-my-followers', [CandidatController::class, 'getMyFollowers'])->name('allFollowers');

        //count followers

        Route::get('/count-my-followers', [CandidatController::class, 'countMyFollowers'])->name('countFollowers');

    });


});


Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::prefix('/admin')->group(function () {
        Route::get('/users', [AdminController::class, 'getAllUsers'])->name('users');
        Route::get('/users/{id}', [AdminController::class, 'getUser'])->name('user');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('deleteUser');


        //election
        //add
        Route::post('/add-election', [AdminController::class, 'addElection'])->name('addElection');

        //update
        Route::put('/update-election/{id}', [AdminController::class, 'updateElection'])->name('updateElection');

        //delete
        Route::delete('/delete-election/{id}', [AdminController::class, 'deleteElection'])->name('deleteElection');

        //updateBanner
        Route::put('/election/{id}/update-banner', [AdminController::class, 'updateBanner'])->name('updateElectionBanner');

        //updateLogo
        Route::put('/election/{id}/update-logo', [AdminController::class, 'updateLogo'])->name('updateLogo');


        //sondage

        //add
        Route::post('/add-sondage', [AdminController::class, 'addSondage'])->name('addSondage');

        //update
        Route::put('/update-sondage/{id}', [AdminController::class, 'updateSondage'])->name('updateSondage');

        //delete
        Route::delete('/delete-sondage/{id}', [AdminController::class, 'deleteSondage'])->name('deleteSondage');

        //all
        Route::get('/sondages', [AdminController::class, 'getAllSondages'])->name('allSondages');

        //election participants

        //add
        Route::post('/elections/add-participant', [AdminController::class, 'addElectionParticipant'])->name('addElectionParticipant');

        //delete

        Route::delete('/elections/delete-participant', [AdminController::class, 'deleteElectionParticipant'])->name('deleteElectionParticipant');

        //calculate result
        Route::get('/elections/{id}/calculate-result', [AdminController::class, 'calculateResult'])->name('calculateResult');

        //get result
        Route::get('/elections/{id}/get-result', [AdminController::class, 'getResultats'])->name('getResult');



        //candidats

        //add

        Route::post('/add-candidat/{id_user}/{id_parti_politique}', [AdminController::class, 'addCandidat'])->name('addCandidat');

        //update

        Route::put('/update-candidat/{id_user}/{id_parti_politique}', [AdminController::class, 'updateCandidat'])->name('updateCandidat');


        //delete

        Route::delete('/delete-candidat/{id_user}', [AdminController::class, 'deleteCandidat'])->name('deleteCandidat');


        //parti politique

        //add

        Route::post('/add-parti-politique', [AdminController::class, 'addPartiPolitique'])->name('addPartiPolitique');

        Route::put('/parti-politique/{id}', [AdminController::class, 'updatePartiPolitique'])->name('updatePartiPolitique');

        Route::delete('/parti-politique/{id}', [AdminController::class, 'deletePartiPolitique'])->name('deletePartiPolitique');


        //type sondage

        Route::post('/add-type-sondage', [AdminController::class, 'addTypeSondage'])->name('addTypeSondage');

        Route::put('/update-type-sondage/{id}', [AdminController::class, 'updateTypeSondage'])->name('updateTypeSondage');



        Route::delete('/delete-type-sondage/{id}', [AdminController::class, 'deleteTypeSondage'])->name('deleteTypeSondage');






    });


});



