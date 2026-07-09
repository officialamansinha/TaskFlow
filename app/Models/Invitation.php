<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Team;

class Invitation extends Model
{
    /** @use HasFactory<\Database\Factories\InvitationFactory> */
    use HasFactory;
    protected $fillable = ['team_id','email','role','token','status'];

    public function team(){
        return $this->belongsTo(Team::class);
    }
}
