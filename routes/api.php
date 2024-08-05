<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::post('/login', [AuthController::class, 'login'])/* ->name('login') */;
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () 
{
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        //return $request->user();
        return new UserResource($request->user());
    });

    Route::get('/users', function (Request $request) {
        return new UserCollection(User::latest()->paginate());
    });

    Route::get('/user/{user}', function (User $user) {
        return new UserResource($user);
    });    
});

