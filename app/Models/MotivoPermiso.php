<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivoPermiso extends Model
{
    use HasFactory;

    protected $table = 'motivo_permiso';
    protected $primaryKey = 'id_motivo_permiso';
    public $timestamps = false;

    protected $fillable = [
        'nombre_motivo_permiso',
        'estado_motivo_permiso',
        'fecha_reg_motivo_permiso',
        'fecha_mod_motivo_permiso',
        'usuario_motivo_permiso',
        'ip_motivo_permiso'
    ];
}
