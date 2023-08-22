<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permiso';
    protected $primaryKey = 'id_permiso';
    public $timestamps = false;

    protected $fillable = [
        'id_empleado',
        'id_plaza_asignada',
        'id_motivo_permiso',
        'id_tipo_permiso',
        'fecha_inicio_permiso',
        'fecha_fin_permiso',
        'destino_permiso',
        'hora_entrada_permiso',
        'hora_salida_permiso',
        'goce_sueldo_permiso',
        'estado_permiso',
        'fecha_reg_permiso',
        'fecha_mod_permiso',
        'usuario_permiso',
        'ip_permiso'
    ];
}
