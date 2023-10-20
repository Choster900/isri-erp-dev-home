<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapaPermiso extends Model
{
    use HasFactory;

    protected $table = 'etapa_permiso';
    protected $primaryKey = 'id_etapa_permiso';
    public $timestamps = false;

    protected $fillable = [
        'id_empleado',
        'id_permiso',
        'id_estado_permiso',
        'id_persona_etapa',
        'observacion_etapa_permiso',
        'fecha_reg_etapa_permiso',
        'usuario_etapa_permiso',
        'ip_etapa_permiso',
        'estado_etapa_permiso'
    ];
}
