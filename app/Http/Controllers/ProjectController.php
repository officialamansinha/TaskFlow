<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;  

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teamIds = DB::Table('team_user')
                ->where('user_id',$request->user()->id)
                ->pluck('team_id');

        $project = Project::whereIN('team_id',$teamIds)->get();

        return response()->json($project);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $teamIds = DB::Table('team_user')
                ->where('user_id',$request->user()->id)
                ->pluck('team_id');
        if(!$teamIds->contains($request->team_id) ){
            return response()->json([
                'message' => 'Forbidden',
            ],403);
        }
        $project = Project::create([
            'team_id' => $request->team_id ,
            'name' => $request->name ,
            'created_by' => $request->user()->id,
        ]);

        return response()->json($project,201);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $project = Project::find($id);
        if(!$project){
            return response()->json(['message' => 'Not Found '],404);
        }
        $isMember  = DB::Table('team_user')
                ->where('team_id',$project->team_id)
                ->where('user_id',$request->user()->id)
                ->exists();
        if(!$isMember ){
            return response()->json(['message' => 'Forbidden '],403);
        }
        return response()->json($project,200);

    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        $project = Project::find($id);

        if(!$project){
            return response()->json(['message' => 'Project Not Found'],404);
        }

        //Check if this Project has been created by him or not
        if($project->created_by != $request->user()->id){
            return response()->json(['message' => 'No Access'],403);
        }

        $project->update(['name' => $request->name ?? $project->name]);

        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $project = Project::find($id);
        if(!$project){
            return response()->json(['message' => 'Project Not Found'],404);
        }
        //Check if this Project has been created by him or not
        if($project->created_by != $request->user()->id){
            return response()->json(['message' => 'No Access'],403);
        }
        $project->delete();
        return response()->json(['message'=>'Project Deleted']);

    }
}
