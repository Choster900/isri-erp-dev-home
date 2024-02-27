<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministradorAdquisicion extends Model
{
    use HasFactory;

    protected $table = 'administrador_adquisicion';
    protected $primaryKey = 'id_administrador_adquisicion';
    public $timestamps = false;

    protected $fillable = [
        'id_doc_adquisicion',
        'id_empleado',
        'estado_admon_adquisicion',
        'fecha_reg_admon_adquisicion',
        'fecha_mod_admon_adquisicion',
        'usuario_admon_adquisicion',
        'ip_admon_adquisicion',
    ];
}
