<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadInstitucional extends Model
{
    use HasFactory;

    protected $table = 'actividad_institucional';
    protected $primaryKey = 'id_actividad_institucional';
    public $timestamps = false;

    protected $fillable = [
        'id_linea_trabajo',
        'codigo_actividad_institucional',
        'nombre_actividad_institucional',
        'estado_actividad_institucional',
        'fecha_reg_actividad_institucional',
        'fecha_mod_actividad_institucional',
        'usuario_actividad_institucional',
        'ip_actividad_institucional',
    ];
}
