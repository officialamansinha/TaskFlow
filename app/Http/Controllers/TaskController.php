<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teamIds = DB::Table('team_user')
                ->where('user_id',$request->user()->id)
                ->pluck('team_id');
        $projectIds = Project::whereIn('team_id',$teamIds)
                    ->pluck('id');
        $task = Task::whereIn('project_id',$projectIds)->get();
        return response()->json($task,200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $task = Task::find($id);
        if(!$task){
            return response()->json([
                'message' => 'Task Not Found'
            ],404);
        }
        $project = Project::find($task->project_id);

        //Authorization 
        $is_Member = DB::table('team_user')
                    ->where('team_id',$project->team_id)
                    ->where('user_id',$request->user()->id)
                    ->exists();

        if(!$is_Member){
            return response()->json([
                'message' => 'Forbidden'
            ],403);
        }
        return response()->json($task,200);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        //verify project exists or not
        $project = Project::find($request->project_id);

        if(!$project){
            return response()->json([
                'message' => 'project doesnot exists'
            ],404);
        }

        //Authorization 
        $is_Member = DB::table('team_user')
                    ->where('team_id',$project->team_id)
                    ->where('user_id',$request->user()->id)
                    ->exists();

        if(!$is_Member){
            return response()->json([
                'message' => 'Forbidden'
            ],403);
        }               
        $task = Task::create([
            'project_id' => $request->project_id,
            'assigned_to' => $request->assigned_to,
            'name' => $request->name,
            'due_date' => $request->due_date,
            'status' => $request->status ?? 'pending'
        ]);

        return response()->json($task,201);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request,$id)
    {
        $task = Task::find($id);
        if(!$task){
            return response()->json([
                'message' => 'Task Not Found'
            ],404);
        }
        $project = Project::find($task->project_id);

        //Authorization 
        $is_Member = DB::table('team_user')
                    ->where('team_id',$project->team_id)
                    ->where('user_id',$request->user()->id)
                    ->exists();

        if(!$is_Member){
            return response()->json([
                'message' => 'Forbidden'
            ],403);
        }
        $task->update([
            'name' => $request->name ?? $task->name,
            'due_date' => $request->due_date ?? $task->due_date,
            'status' => $request->status ?? $task->status,
            'assigned_to' => $request->assigned_to ?? $task->assigned_to
        ]);

        return response()->json($task,200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        $task = Task::find($id);
        if(!$task){
            return response()->json([
                'message' => 'Task Not Found'
            ],404);
        }
        $project = Project::find($task->project_id);

        //Authorization 
        $is_Member = DB::table('team_user')
                    ->where('team_id',$project->team_id)
                    ->where('user_id',$request->user()->id)
                    ->exists();

        if(!$is_Member){
            return response()->json([
                'message' => 'Forbidden'
            ],403);
        }

        $task->delete();
        return response()->json([
            'message' => 'Task Deleted Successfully'
        ]);
        
    }
}
