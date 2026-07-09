<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\User;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;
    protected $fillable =['project_id','assigned_to','name','status','due_date'];
    protected $casts = ['due_date'=>'date'];

    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function assignee(){
        return $this->belongsTo(User::class,'assigned_to');
    }
}
