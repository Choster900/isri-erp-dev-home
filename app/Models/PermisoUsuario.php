<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PermisoUsuario extends Pivot
{
    use HasFactory;

    protected $table = 'permiso_usuario';
    protected $primaryKey = 'id_permiso_usuario';
    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'id_usuario',
        'estado_permiso_usuario',
        'fecha_reg_permiso_usuario',
        'fecha_mod_permiso_usuario',
        'usuario_permiso_usuario',
        'ip_permiso_usuario',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
}
