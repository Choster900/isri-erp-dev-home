<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TituloProfesional extends Model
{
    use HasFactory;

    protected $table = 'titulo_profesional';
    protected $primaryKey = 'id_titulo_profesional';
    public $timestamps = false;

    protected $fillable = [
        'nombre_titulo_profesional',
        'codigo_titulo_profesional',
    ];
}
