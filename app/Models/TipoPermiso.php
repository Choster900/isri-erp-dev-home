<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPermiso extends Model
{
    use HasFactory;

    protected $table = 'tipo_permiso';
    protected $primaryKey = 'id_tipo_permiso';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_permiso',
        'tiempo_max_tipo_permiso',
        'estado_tipo_permiso',
        'fecha_reg_tipo_permiso',
        'fecha_mod_tipo_permiso',
        'usuario_tipo_permiso',
        'ip_tipo_permiso'
    ];
}
