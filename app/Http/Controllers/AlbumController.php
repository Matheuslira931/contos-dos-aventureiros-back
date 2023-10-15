<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AlbumController extends Controller
{

    public $imagemAlbumGenerica;

    public function __construct()
    {
      $this->imagemAlbumGenerica = public_path("\\" . "imagem" . "\\") . "imagem-album-generico.png";
    }

    public function consultarAlbuns(){

        $albuns = Album::get();

        if($albuns){
            return $albuns;
        }else{
            return response()->json(['errors' => 'Não foi encontrado álbuns'], 422);
        }

    }

    public function exibirAlbum($albumId){

        $album = Album::find($albumId);

        if($album){

            $audios =  DB::table('audio')
            ->where('album_id', '=', $album->id)
            ->get();

            $album->audios = $audios;

            return $album;

        }else{

            return response()->json(['errors' => 'Álbum não encontrado'], 422);

        }

    }

    public static function deletarAlbum($albumId){

        $album = Album::find($albumId);

        if($album){

            $audios =  DB::table('audio')
            ->where('album_id', '=', $album->id)
            ->get();

            if($audios){
                foreach ($audios as $audio) {
                    AudioController::deletarAudio($audio->id);
                }
            }

            $album->delete();
            return $album;

        }else{

            return response()->json(['errors' => 'Álbum não encontrado'], 422);

        }

    }

    public function criarAlbum(Request $request){

        $nomeImagem = '';

        $rules = [
            'titulo' => [
                'required'
            ],
            'usuario_id' => [
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

        if($request->file('imagem')){
            $nomeImagem = GerenciadorArquivo::adicionarImagem($request->titulo, $request->file('imagem'));
        }else{
            $nomeImagem = $this->imagemAlbumGenerica;
        }

        $album = Album::Create([
            'titulo' => $request->titulo,
            'imagem' => $nomeImagem,
            'descricao' => $request->descricao,
            'usuario_id' => $request->usuario_id,
        ]);

        return $album;

    }

    public function atualizarAlbum(Request $request, $albumId){

        $album = Album::find($albumId);

        if($album){

            $rules = [
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

            $nomeImagem = $album->imagem;

            if($request->file('imagem')){

                if($album->imagem == $this->imagemAlbumGenerica){
                    $nomeImagem = GerenciadorArquivo::adicionarImagem($request->titulo, $request->file('imagem'));
                }elseif($album->imagem){
                    if(GerenciadorArquivo::removerImagem($album->imagem)){
                        $nomeImagem = GerenciadorArquivo::adicionarImagem($request->titulo, $request->file('imagem'));
                    }
                }

            }

            $album->update([
                'titulo' => $request->titulo,
                'imagem' => $nomeImagem,
                'descricao' => $request->descricao,
            ]);

            return $album;

        }{
            return response()->json(['errors' => 'Álbum não encontrado'], 422);
        }

    }

    public function pesquisarAlbum(Request $request){

        $pesquisa = '%' . $request->textoPesquisa . '%';

        $albums =  DB::table('albums')
        ->where('titulo', 'like', $pesquisa)
        ->get();

        if(count($albums) > 0){
            return $albums;
        }else{
            return response()->json(['errors' => 'Álbum não encontrado'], 422);
        }

    }

}
