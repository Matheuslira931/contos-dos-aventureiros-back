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
    Route::delete('deletar-usuario/{usuarioId}', [App\Http\Controllers\UserController::class,'deletarUsuario']);
    Route::put('atualizar-usuario/{usuarioId}', [App\Http\Controllers\UserController::class,'atualizarUsuario']);

    // Álbum
    Route::post('criar-album', [App\Http\Controllers\AlbumController::class,'criarAlbum']);
    Route::post('atualizar-album/{albumId}', [App\Http\Controllers\AlbumController::class,'atualizarAlbum']);
    Route::delete('deletar-album/{albumId}', [App\Http\Controllers\AlbumController::class,'deletarAlbum']);

    // Áudio
    Route::post('criar-audio', [App\Http\Controllers\AudioController::class,'criarAudio']);
    Route::post('atualizar-audio/{audioId}', [App\Http\Controllers\AudioController::class,'atualizarAudio']);
    Route::delete('deletar-audio/{audioId}', [App\Http\Controllers\AudioController::class,'deletarAudio']);

    // Favorito
    Route::get('consultar-favoritos/{usuarioId}', [App\Http\Controllers\FavoritoController::class,'consultarFavoritos']);
    Route::post('criar-favorito', [App\Http\Controllers\FavoritoController::class,'criarFavorito']);
    Route::delete('deletar-favorito/{favoritoId}', [App\Http\Controllers\FavoritoController::class,'deletarFavorito']);

});

// Usuário
Route::post('criar-usuario', [App\Http\Controllers\UserController::class,'criarUsuario']);
Route::get('consultar-usuarios', [App\Http\Controllers\UserController::class,'consultarUsuarios']);
Route::get('exibir-usuario/{usuarioId}', [App\Http\Controllers\UserController::class,'exibirUsuario']);

// Álbum
Route::get('consultar-albuns', [App\Http\Controllers\AlbumController::class,'consultarAlbuns']);
Route::get('exibir-album/{albumId}', [App\Http\Controllers\AlbumController::class,'exibirAlbum']);
Route::get('pesquisar-album', [App\Http\Controllers\AlbumController::class,'pesquisarAlbum']);

// Áudio
Route::get('consultar-audios', [App\Http\Controllers\AudioController::class,'consultarAudios']);
Route::get('exibir-audio/{audioId}', [App\Http\Controllers\AudioController::class,'exibirAudio']);
