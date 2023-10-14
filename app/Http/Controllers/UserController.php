<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AnuncioController;

class UserController extends Controller
{
    public function consultarUsuarios(){

        $users = User::get();

        if($users){
            return $users;
        }else{
            return response()->json(['errors' => 'Não foi encontrado usuário(s)'], 422);
        }

    }

    public function criarUsuario(Request $request){

        $rules = [
            'nome' => [
                'required'
            ],
            'email' => [
                'required',
                'unique:users,email'
            ],
            'senha' => [
                'required',
            ]
        ];

        $messages = [
            'required' => "Este campo é de preenchimento obrigatório.",
        ];

        $validated = Validator::make($request->all(), $rules, $messages);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $user = User::Create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => bcrypt($request->senha),
        ]);

        return $user;

    }

    public function deletarUsuario($usuarioId){

        $user = User::find($usuarioId);

        if($user){

            $albuns =  DB::table('albums')
            ->where('usuario_id', '=', $user->id)
            ->get();

            if($albuns){
                foreach ($albuns as $album) {
                    AlbumController::deletarAlbum($album->id);
                }
            }

            $user->delete();
            return $user;
        }else{
            return response()->json(['errors' => 'Usuário não encontrado'], 422);
        }

    }

    public function exibirUsuario($usuarioId){

        $user = User::find($usuarioId);

        if($user){

            $albums =  DB::table('albums')
            ->where('usuario_id', '=', $user->id)
            ->get();

            $user->albums = $albums;

            return $user;

        }else{

            return response()->json(['errors' => 'Usuário não encontrado'], 422);

        }

    }

    public function atualizarUsuario(Request $request, $usuarioId){

        $user = User::find($usuarioId);

        if($user){

            $rules = [
                'nome' => [
                    'required'
                ]
            ];

            $messages = [
                'required' => "Este campo é de preenchimento obrigatório.",
            ];

            $validated = Validator::make($request->all(), $rules, $messages);

            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()], 422);
            }


            $user->update([
                'nome' => $request->nome,
            ]);

            return $user;

        }{
            return response()->json(['errors' => 'Usuário não encontrado'], 422);
        }

    }

}
