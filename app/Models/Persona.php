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
        'id_usuario',
        'id_genero',
        'id_estado_civil',
        'id_nivel_educativo',
        'id_municipio',
        'id_profesion',
        'pnombre_persona',
        'snombre_persona',
        'tnombre_persona',
        'papellido_persona',
        'sapellido_persona',
        'tapellido_persona',
        'telefono_persona',
        'dui_persona',
        'email_persona',
        'nombre_conyuge_persona',
        'nombre_madre_persona',
        'nombre_padre_persona',
        'fecha_nac_persona',
        'fecha_reg_persona',
        'fecha_mod_persona',
        'estado_persona',
        'usuario_persona',
        'ip_persona'
    ];


}