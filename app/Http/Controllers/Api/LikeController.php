<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Likes;
use Illuminate\Http\Request;



class LikeController extends Controller
{


    public function addLike(Request $request)
    {


        $request->validate([
            'id_post' => 'required|integer|exists:posts,id',
            'id_user' => 'required|integer|exists:users,id',
        ]);

        //verifier si le like existe deja
        $like = Likes::where('id_post', $request->id_post)
            ->where('id_user', $request->id_user)
            ->first();

        if ($like) {
            return response()->json([
                'message' => 'Like already exists',
                'data' => $like,
            ], 409);
        }

        $like = new Likes();
        $like->id_post = $request->input('id_post');
        $like->id_user = $request->input('id_user');
        $like->save();

        return response()->json([
            'message' => 'Like added successfully'
        ], 200);
    }

    public function getAllLikes()
    {

        $likes = Likes::all();
        if ($likes->count() > 0){
            return response()->json([
                'data' => $likes
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de likes'
            ], 404);
        }

    }


   public function getPostLikes(int $id)
    {
        $likes = Likes::where('id_post', $id)->get();
        if ($likes->count() > 0){
            return response()->json([
                'data' => $likes
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de likes'
            ], 404);
        }
    }

    public function getUserLikes(int $id)
    {
        $likes = Likes::where('id_user', $id)->get();
        if ($likes->count() > 0){
            return response()->json([
                'likes' => $likes
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de likes'
            ], 404);
        }
    }

    public function deleteLike(int $id)
    {
        $like = Likes::find($id);
        if (!$like) {
            return response()->json([
                'message' => 'Like non trouvé'
            ], 404);
        }
        $like->delete();
        return response()->json([
            'message' => 'Like deleted successfully'
        ], 200);
    }

    public function deleteLikeByUserAndPost(int $id_user, int $id_post)
    {
        $like = Likes::where('id_user', $id_user)->where('id_post', $id_post)->first();
        if (!$like) {
            return response()->json([
                'message' => 'Like non trouvé'
            ], 404);
        }
        $like->delete();
        return response()->json([
            'message' => 'Like deleted successfully'
        ], 200);
    }

}
