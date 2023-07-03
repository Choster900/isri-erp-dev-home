<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    
    protected $table = 'empleado';
    protected $primaryKey = 'id_empleado';
    public $timestamps = false;

    protected $fillable = [
        'id_persona',
        'id_tipo_pension',
        'id_banco',
        'id_titulo_profesional',
        'codigo_empleado',
        'nup_empleado',
        'isss_empleado',
        'cuenta_banco_empleado',
        'fecha_contratacion_empleado',
        'email_institucional_empleado',
        'email_alternativo_empleado',
        'fecha_reg_empleado',
        'fecha_mod_empleado',
        'usuario_empleado',
        'ip_empleado'
    ];

    public function persona()
    {
        return $this->hasOne('App\Models\Persona','id_persona','id_persona');
    }
    // public function dependencia()
    // {
    //     return $this->belongsTo('App\Models\Dependencia','id_depedencia','id_dependencia');
    // }
}
