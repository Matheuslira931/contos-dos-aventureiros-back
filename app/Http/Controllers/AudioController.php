<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Audio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AudioController extends Controller
{
    public function consultarAudios(){

        $audios = Audio::get();

        if($audios){
            return $audios;
        }else{
            return response()->json(['errors' => 'Não foi encontrado áudios'], 422);
        }

    }

    public function criarAudio(Request $request){

        $nomeImagem = '';
        $sequencia = "1";

        $rules = [
            'audio' => [
                'required'
            ],
            'titulo' => [
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

        if($request->file('audio')){
            $nomeAudio = GerenciadorArquivo::adicionarAudio($request->titulo, $request->file('audio'));
        }

        $audio = Audio::Create([
            'titulo' => $request->titulo,
            'nome' => $nomeAudio,
            'sequencia' => $sequencia,
            'album_id' => $request->album_id,
        ]);

        return $audio;

    }

    public function atualizarAudio(Request $request, $audioId){

        $audio = Audio::find($audioId);

        if($audio){

            $rules = [
                'audio' => [
                    'required'
                ],
                'titulo' => [
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

            $nomeAudio = $audio->nome;

            if($request->file('audio')){
                if(GerenciadorArquivo::removerAudio($audio->nome)){
                    $nomeAudio = GerenciadorArquivo::adicionarAudio($request->titulo, $request->file('audio'));
                }
            }

            $audio->update([
                'titulo' => $request->titulo,
                'nome' => $nomeAudio,
            ]);

            return $audio;

        }{
            return response()->json(['errors' => 'Áudio não encontrado'], 422);
        }

    }

}
