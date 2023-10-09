<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class GerenciadorArquivo extends Controller
{

    public static function adicionarImagem($titulo, $arquivo){

        return self::adicionarArquivo($titulo, $arquivo, "imagem");

    }

    public static function removerImagem($nomeArquivo){

        return self::removerArquivo($nomeArquivo, "imagem");

    }

    public static function adicionarAudio($titulo, $arquivo){

        return self::adicionarArquivo($titulo, $arquivo, "audio");

    }

    private static function removerArquivo($nomeArquivo, $diretorio){

        $excluido = false;
        $caminho = public_path("\\" . $diretorio . "\\") . $nomeArquivo;
        if(File::delete($caminho)){
            $excluido = true;
        }

        return $caminho;

    }
    private static function adicionarArquivo($titulo, $arquivo, $diretorio){

        $nomeArquivo = $titulo.'-file-'.time().rand(1,1000).'.'.$arquivo->extension();
        $nomeArquivo = str_replace(' ', '', $nomeArquivo);
        $arquivo->move(public_path($diretorio),$nomeArquivo);

        return $nomeArquivo;

    }

}
