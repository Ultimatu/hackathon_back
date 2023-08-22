<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function addPost(Request $request)
    {
        $id_user = auth()->user()->id;
        $id_candidat = Candidat::where('id_user', $id_user)->first()->id;

        $request['id_candidat'] = $id_candidat;
        $request->validate([
            'id_candidat' => 'required|integer|exists:candidats,id',
            'titre' => 'required|string',
            'description' => 'required|string',
            'url_media' => 'nullable|mimes:jpeg,png,gif,mp4,mov,avi',
        ]);

        if ($request->hasFile("url_media")){
            $file = $request->file('url_media');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $file->move('storage/posts', $fileNameToStore);
            $nameToFront = 'storage/posts/' . $fileNameToStore;
        }
        else{
            $nameToFront = 'storage/posts/posts';
        }


        $post = new Post();
        $post->id_candidat = $request->input('id_candidat');
        $post->titre = $request->input('titre');
        $post->description = $request->input('description');
        $post->url_media = $nameToFront;
        $post->save();

        return response()->json([
            'message' => 'Post added successfully'
        ], 200);
    }

    public function getAllPosts()
    {

        $posts = Post::all();
        if ($posts->count() > 0 ){
            $paginatedPosts = Post::paginate(10);
            return response()->json([
                'data' => $paginatedPosts
            ], 200);
        }

        else{
            return response()->json([
                'message' => 'Pas de publications'
            ], 404);
        }

    }


    public function getPost(int $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Publication non trouvée'
            ], 404);
        }
        return response()->json([
            'data' => $post
        ], 200);
    }


    public function deletePost(int $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Publication non trouvée'
            ], 404);
        }
        $post->delete();
        return response()->json([
            'message' => 'Publication supprimée avec succès'
        ], 200);
    }


    public function deletePostByCandidatAndId(int $id, int $id_candidat)
    {
        $post = Post::where('id', $id)->where('id_candidat', $id_candidat)->first();
        if (!$post) {
            return response()->json([
                'message' => 'Publication non trouvée'
            ], 404);
        }
        $post->delete();
        return response()->json([
            'message' => 'Publication supprimée avec succès'
        ], 200);
    }


    public function updatePost(int $id, Request $request)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Publication non trouvée'
            ], 404);
        }

        $request->validate([
            'titre' => 'required|string',
            'description' => 'required|string',
        ]);

        $post->titre = $request->input('titre');
        $post->description = $request->input('description');

        $post->save();

        return response()->json([
            'message' => 'Publication modifiée avec succès'
        ], 200);
    }

    public function updatePostPhoto(int $id, Request $request)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Publication non trouvée'
            ], 404);
        }

        $request->validate([
            'url_media' => 'required|mimes:jpeg,png,gif,mp4,mov,avi',
        ]);

        $file = $request->file('url_media');
        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
        $file->move('storage/posts', $fileNameToStore);
        $nameToFront = 'storage/posts/' . $fileNameToStore;

        //delete old logo
        Storage::delete($post->url_media);
        $post->url_media = $nameToFront;

        $post->save();

        return response()->json([
            'message' => 'Photo modifiée avec succès'
        ], 200);
    }


    public function searchPost(Request $request)
    {
        $request->validate([
            'search' => 'required|string'
        ]);

        $search = $request->input('search');

        $posts = Post::search($search)->get();

        if ($posts->count() > 0) {
            return response()->json([
                'posts' => $posts
            ], 200);
        } else {
            return response()->json([
                'message' => 'Pas de publications'
            ], 404);
        }
    }


    public function getAllPostByCandidat(int $id)
    {
        $posts = Post::where('id_candidat', $id)->get();
        if ($posts->count() > 0) {
            $paginatedPosts = Post::where('id_candidat', $id)->paginate(10);
            return response()->json([
                'posts' => $paginatedPosts
            ], 200);
        } else {
            return response()->json([
                'message' => 'Pas de publications'
            ], 404);
        }
    }


    public function getAllPostByCandidatAndSort(int $id, Request $request)
    {
        $request->validate([
            'sort' => 'required|string'
        ]);

        $sort = $request->input('sort');

        $posts = Post::where('id_candidat', $id)->sort($sort)->get();

        if ($posts->count() > 0) {
            return response()->json([
                'posts' => $posts
            ], 200);
        } else {
            return response()->json([
                'message' => 'Pas de publications'
            ], 404);
        }
    }


    public function getAllByFilter(Request $request)
    {
        $request->validate([
            'filter' => 'required|integer|exists:candidats,id'
        ]);

        $filter = $request->input('filter');

        $posts = Post::filter($filter)->get();

        if ($posts->count() > 0) {
            return response()->json([
                'posts' => $posts
            ], 200);
        } else {
            return response()->json([
                'message' => 'Pas de publications'
            ], 404);
        }
    }



}
