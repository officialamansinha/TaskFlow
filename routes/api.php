<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\InvitationController;

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::apiResource('teams',TeamController::class);
    Route::apiResource('projects',ProjectController::class);
    Route::apiResource('tasks',TaskController::class);
    Route::apiResource('invitations',InvitationController::class);
    Route::post('/invitations/accept',[InvitationController::class,'accept']);
});
