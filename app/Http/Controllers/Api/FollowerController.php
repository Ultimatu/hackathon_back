<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use Illuminate\Http\Request;



class FollowerController extends Controller
{

    public function follow(Request $request)
    {
        $request->validate([
            'id_candidat' => 'required|exists:candidats,id',
            'id_user' => 'required|exists:users,id',
        ]);

        $follower = Follower::where('id_candidat', $request->id_candidat)->where('id_user', auth()->user()->id)->first();
        if ($follower) {
            return response()->json([
                'message' => 'Vous suivez déjà ce candidat',
                'follower' => $follower
            ], 200);
        }

        $follower = Follower::create([
            'id_candidat' => $request->id_candidat,
            'id_user' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'Vous suivez ce candidat',
            'follower' => $follower
        ], 201);
    }


    public function unfollow(Request $request)
    {
        $request->validate([
            'id_candidat' => 'required|exists:candidats,id',
        ]);

        $follower = Follower::where('id_candidat', $request->id_candidat)->where('id_user', auth()->user()->id)->first();
        if (!$follower) {
            return response()->json([
                'message' => 'Vous ne suivez pas ce candidat',
            ], 200);
        }

        $follower->delete();

        return response()->json([
            'message' => 'Vous ne suivez plus ce candidat',
        ], 200);
    }


    public function getFollowers(int $id_candidat)
    {
        $followers = Follower::where('id_candidat', $id_candidat)->with('user')->get();

        return response()->json([
            'message' => 'Liste des followers',
            'followers' => $followers
        ], 200);
    }


    public function getFollowings(int $id_user)
    {
        $followings = Follower::where('id_user', $id_user)->get();

        return response()->json([
            'message' => 'Liste des followings',
            'followings' => $followings
        ], 200);
    }



    public function isFollowing(int $id_candidat)
    {
        $follower = Follower::where('id_candidat', $id_candidat)->where('id_user', auth()->user()->id)->first();

        if ($follower) {
            return response()->json([
                'message' => 'Vous suivez ce candidat',
                'follower' => $follower
            ], 200);
        } else {
            return response()->json([
                'message' => 'Vous ne suivez pas ce candidat',
            ], 200);
        }
    }

    public function countFollowers(int $id_candidat)
    {
        $followers = Follower::where('id_candidat', $id_candidat)->count();

        return response()->json([
            'message' => 'Nombre de followers',
            'followers' => $followers
        ], 200);
    }

    public function countFollowings(int $id_user)
    {
        $followings = Follower::where('id_user', $id_user)->count();

        return response()->json([
            'message' => 'Nombre de followings',
            'followings' => $followings
        ], 200);
    }

    public function showFollowers(int $id_candidat)
    {
        //select candidat followers with users datas
        $followers = Follower::where('id_candidat', $id_candidat)->with('user')->get();

        return response()->json([
            'message' => 'Liste des followers',
            'followers' => $followers
        ], 200);
    }

}
