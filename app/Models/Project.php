<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\User;
use App\Models\Task;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;
    protected $fillable = ['team_id','name','created_by','status'];
    protected $casts = ['status' => 'boolean'];

    public function team(){
        return $this->belongsTo(Team::class);
    }
    public function creator(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function tasks(){
        return $this->hasMany(Task::class);
    }

}
