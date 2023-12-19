<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NoteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('auth/log-out', [UserController::class, ' logOut']);
    Route::get('auth/check', [UserController::class, 'checkAuth']);
    Route::get('notes/get-notes', [NoteController::class, 'index']);
    Route::post('notes/add-note', [NoteController::class, 'addNote']);
    Route::delete('notes/delete-note/{id}', [NoteController::class, 'deleteNote']);
    Route::put('notes/update-note/{id}', [NoteController::class, 'updateNote']);
});

Route::post('auth/sign-up', [UserController::class, 'signUp']);
Route::post('auth/log-in', [UserController::class,  'logIn']);
