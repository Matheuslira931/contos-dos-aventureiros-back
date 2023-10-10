<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [App\Http\Controllers\AuthController::class,'login']);
Route::middleware(['jwt.auth'])->group(function () {

    // Usuário
    Route::post('logout', [App\Http\Controllers\AuthController::class,'logout']);

    // Álbum
    Route::post('criar-album', [App\Http\Controllers\AlbumController::class,'criarAlbum']);
    Route::post('atualizar-album/{albumId}', [App\Http\Controllers\AlbumController::class,'atualizarAlbum']);

    // Áudio
    Route::post('criar-audio', [App\Http\Controllers\AudioController::class,'criarAudio']);
    Route::post('atualizar-audio/{audioId}', [App\Http\Controllers\AudioController::class,'atualizarAudio']);


});

// Usuário
Route::post('criar-usuario', [App\Http\Controllers\UserController::class,'criarUsuario']);
Route::get('consultar-usuarios', [App\Http\Controllers\UserController::class,'consultarUsuarios']);

// Álbum
Route::get('consultar-albuns', [App\Http\Controllers\AlbumController::class,'consultarAlbuns']);

