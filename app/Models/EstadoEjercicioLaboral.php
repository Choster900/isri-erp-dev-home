<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoEjercicioLaboral extends Model
{
    use HasFactory;

    protected $table = 'estado_ejercicio_fiscal';
    protected $primaryKey = 'id_estado_ef';
    public $timestamps = false;

    protected $fillable = [
        'nombre_estado_ef',
        'fecha_reg_estado_ef',
        'fecha_mod_estado_ef',
        'usuario_estado_ef',
        'ip_estado_ef',
    ];
}
