<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoTesoreria extends Model
{
    use HasFactory;

    protected $table = 'empleado_tesoreria';
    protected $primaryKey = 'id_empleado_tesoreria';
    public $timestamps = false;

    protected $fillable = [
        'id_cargo_tesoreria',
        'dui_empleado_tesoreria',
        'nombre_empleado_tesoreria',
        'estado_empleado_tesoreria',
        'fecha_reg_empleado_tesoreria',
        'fecha_mod_empleado_tesoreria',
        'usuario_empleado_tesoreria',
        'ip_empleado_tesoreria',
    ];

    public function recibos_ingreso()
    {
        return $this->hasMany('App\Models\ReciboIngreso', 'id_empleado_tesoreria', 'id_empleado_tesoreria');
    }
}
