<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request) {

        $token = auth('api')->attempt(["email" => $request->email, "password" => $request->senha]);

        if ($token) {

            $usuario =  DB::table('users')
            ->where('email', '=', $request->email)
            ->get();

            $resposta = [
                'token'   => $token,
                'usuario' => $usuario
            ];

            return response()->json($resposta);

        }
        else {
            return response()->json(['error' => "Email ou senha inválido!"],403);
        }
    }

    public function logout(Request $request) {
        auth('api')->logout();
        return response()->json(['msg' => "Logout feito com sucesso!"]);
    }

    public function refresh() {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }

    public function me() {
        return response()->json(Auth()->user());
    }

}
