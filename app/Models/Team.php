<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Project;
use App\Models\Invitation;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;
    protected $fillable = ['name','owner_id','status'];
    protected $casts =['status'=>'boolean'];

    public function owner(){
        return $this->belongsTo(User::class,'owner_id');
    }
    public function members(){
        return $this->belongsToMany(User::class,'team_user')
                ->withPivot('role','status')
                ->withTimestamps();
    }
    public function projects(){
        return $this->hasMany(Project::class,'team_id');
    }
    public function invitations(){
        return $this->hasMany(Invitation::class,'team_id');
    }
}
