<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Favorito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FavoritoController extends Controller
{
    public function consultarFavoritos($usuarioId){

        $albumsFavoritos = DB::table('albums')
        ->join('favoritos', 'albums.id', '=', 'favoritos.album_id')
        ->select('favoritos.id AS favorito_id',
                 'albums.*')
        ->where('favoritos.usuario_id', '=', $usuarioId)
        ->get();

        if(count($albumsFavoritos) > 0){
            return $albumsFavoritos;
        }else{
            return response()->json(['errors' => 'Você não possui nenhum favorito(s)'], 422);
        }

    }

    public function criarFavorito(Request $request){

        $rules = [
            'usuario_id' => [
                'required'
            ],
            'album_id' => [
                'required'
            ],
        ];

        $messages = [
            'required' => "Este campo é de preenchimento obrigatório.",
        ];

        $validated = Validator::make($request->all(), $rules, $messages);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }


        $favorito = Favorito::Create([
            'usuario_id' => $request->usuario_id,
            'album_id' => $request->album_id,
        ]);

        return $favorito;

    }

    public static function deletarFavorito($favoritoId){

        $favorito = Favorito::find($favoritoId);

        if($favorito){

            $favorito->delete();
            return $favorito;

        }else{

            return response()->json(['errors' => 'Favorito não encontrado'], 422);

        }

    }

}
