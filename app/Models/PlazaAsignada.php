<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlazaAsignada extends Model
{
    use HasFactory;

    protected $table = 'plaza_asignada';
    protected $primaryKey = 'id_plaza_asignada';
    public $timestamps = false;

    protected $fillable = [
        'id_empleado',
        'id_lt',
        'id_dependencia',
        'id_det_plaza',
        'salario_plaza_asignada',
        'partida_plaza_asignada',
        'subpartida_plaza_asignada',
        'fecha_plaza_asignada',
        'estado_plaza_asignada',
        'fecha_reg_plaza_asignada',
        'fecha_mod_plaza_asignada',
        'usuario_plaza_asignada',
        'ip_plaza_asignada',
    ];

    public function empleado()
    {
        return $this->belongsTo('App\Models\Empleado','id_empleado','id_empleado');
    }
    public function dependencia()
    {
        return $this->belongsTo('App\Models\Dependencia','id_dependencia','id_dependencia');
    }
}
