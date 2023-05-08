<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $table = 'persona';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $fillable = [
        'pnombre_persona',
        'snombre_persona',
        'tnombre_persona',
        'papellido_persona',
        'sapellido_persona',
        'tapellido_persona',
        'telefono_persona',
        'dui_persona',
        'email_persona',
        'id_genero',
        'id_estado_civil',
        'nombre_conyuge_persona',
        'nombre_madre_persona',
        'nombre_padre_persona',
        'fecha_nac_persona',
        'id_nivel_educativo',
        'id_municipio',
        'id_profesion',
        'estado_persona',
    ];


}