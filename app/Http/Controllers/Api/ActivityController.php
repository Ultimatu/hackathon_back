<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function addActivity(Request $request)
    {
        $request->validate([
            'id_candidat' => 'required|integer|exists:candidates,id',
            'description' => 'required|string',
            'nom' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
        ]);

        Activities::create($request->all());

        return response()->json(['message' => 'Activity added successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_candidat' => 'required|integer|exists:candidates,id',
            'description' => 'required|string',
            'nom' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
        ]);

        $activity = Activities::find($id);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $activity->update($request->all());

        return response()->json(['message' => 'Activity updated successfully'], 200);
    }

    public function destroy($id)
    {
        $activity = Activities::find($id);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $activity->delete();

        return response()->json(['message' => 'Activity deleted successfully'], 200);
    }


    public function getAllActivities()
    {

        $activities = Activities::all();
        if ($activities->count() > 0) {
            return response()->json([
                'data' => $activities
            ], 200);
        } else {
            return response()->json([
                'message' => 'Pas d\'activités'
            ], 404);
        }
    }


    public function getAllActivitiesByCandidate(int $id)
    {
        $activities = Activities::where('id_candidat', $id)->get();
        if ($activities->count() > 0 && $activities->count() <= 10) {
            return response()->json([
                'data' => $activities
            ], 200);
        } else {
            return response()->json([
                'message' => 'Pas d\'activités'
            ], 404);
        }
    }


    public function getActivity(int $id)
    {
        $activity = Activities::find($id);
        if (!$activity) {
            return response()->json([
                'message' => 'Activity not found'
            ], 404);
        }
        return response()->json([
            'data' => $activity
        ], 200);
    }


    



}
