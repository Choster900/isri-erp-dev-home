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

    /* protected $fillable = [
    'dui_tesorero',
    'nombre_tesorero',
    'estado_tesorero',
    'fecha_reg_tesorero',
    'fecha_mod_tesorero',
    'usuario_tesorero',
    'ip_tesorero',
    ]; */

    protected $fillable = [
        'id_empleado_tesoreria',
        'id_cargo_tesoreria',
        'dui_empleado_tesoreria',
        'nombre_empleado_tesoreria',
        'estado_empleado_tesoreria',
        'fecha_reg_empleado_tesoreria',
        'fecha_mod_empleado_tesoreria',
        'usuario_empleado_tesoreria',
        'ip_empleado_tesoreria',


    ];


    public function Quedan()
    {
        return $this->hasMany(Quedan::class, "id_empleado_tesoreria", "id_empleado_tesoreria");
    }
}