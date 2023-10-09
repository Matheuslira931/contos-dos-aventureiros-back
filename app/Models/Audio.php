<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    //use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sequencia',
        'titulo',
        'nome',
        'album_id',
    ];

}
