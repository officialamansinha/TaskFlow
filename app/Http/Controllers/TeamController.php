<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teams = $request->user()->teams;
        return response()->json($teams);
    }

  

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeamRequest $request)
    {
        $team = Team::create([
            'name' => $request->name,
            'owner_id' => $request->user()->id,
        ]);

        $team->members()->syncWithoutDetaching([
            $request->user()->id => [  
                'role' => 'owner',   
                'status' => true,
            ]
        ]);
        return response()->json($team,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $team = Team::find($id);
        if(!$team){
            return response()->json([
                'message' => 'Team Not Found'
            ],404);
        }
        // Temporary manual check — real Policy comes in Stage 5
        $isMember = $request->user()->teams->contains($team->id);

        if (! $isMember) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($team);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamRequest $request, $id)
    {
        $team = Team::find($id);

        if (! $team) {
            return response()->json(['message' => 'Team not found'], 404);
        }
        $isOwner = $request->user()->ownedTeams->contains($team->id);
        if (!$isOwner) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $team->update([
            'name' => $request->name ?? $team->name,
        ]);

        return response()->json($team);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
         $team = Team::find($id);

        if (! $team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $isOwner = $request->user()->ownedTeams->contains($team->id);

        if (!$isOwner) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $team->delete();

        return response()->json(['message' => 'Team deleted']);
    }
}
