<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadPresupuestaria extends Model
{
    use HasFactory;

    protected $table = 'unidad_presupuestaria';
    protected $primaryKey = 'id_unidad_ppto';
    public $timestamps = false;

    protected $fillable = [
        'codigo_unidad_ppto',
        'nombre_unidad_ppto',
        'estado_unidad_ppto',
        'fecha_reg_unidad_ppto',
        'fecha_mod_unidad_ppto',
        'usuario_unidad_ppto',
        'ip_unidad_ppto',
    ];

    public function lineas_trabajo()
    {
        return $this->hasMany('App\Models\LineaTrabajo', 'id_unidad_ppto', 'id_unidad_ppto');
    }
}
