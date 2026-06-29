<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\StoreInvitationRequest;
use App\Http\Requests\AcceptInvitationRequest;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $team_ids = DB::table('team_user')
                    ->where('user_id',$request->user()->id)
                    ->pluck('team_id');
        $team_ids = Team::where('owner_id',$request->user()->id)
                    ->pluck('id');
        $invitation = Invitation::whereIn('team_id',$team_ids)->get();
        return response()->json($invitation,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvitationRequest $request)
    {
        $team = Team::find($request->team_id);
        if(!$team){
            return response()->json([
                'message' => 'Forbidden'
            ],404);
        }
        if($team->owner_id != $request->user()->id){
            return response()->json([
                'message' => 'Access Denied'
            ],403);
        }
        $is_Member = DB::table('team_user')
                    ->where('user_id',$request->user()->id)
                    ->pluck('team_id');
        if (!$is_Member->contains($request->team_id)) {
            return response()->json([
                'message' =>'Forbidden'
            ],403);
        }
        $token = Str::random(32);
        $invitation = Invitation::create([
            'team_id' => $request->team_id,
            'email' => $request->email,
            'role' => $request->role ?? 'member',
            'token' => $token,
            'status' => 'pending',
        ]);

        return response()->json($invitation,201);
    }

    public function accept(AcceptInvitationRequest $request)
    {
        $invitation = Invitation::where('token',$request->token)->first();
        if(!$invitation){
            return response()->json([
                'message' =>'Token Not Found'
            ],404);
        }
        if($invitation->status == 'accepted' ){
            return response()->json([
                'message' =>'Invitation was accepted'
            ],200);
        }
        if($invitation->status == 'declined'){
            return response()->json([
                'message' =>'Invitation was declined'
            ],200);
        }
        DB::table('team_user')->insert([
            'team_id' => $invitation->team_id,
            'user_id' => $request->user()->id,
            'role' =>  $invitation->role,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $invitation->update([
            'status' => 'accepted'
        ]);

        return response()->json($invitation);
    }

   
}
