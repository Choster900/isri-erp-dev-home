<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineaTrabajo extends Model
{
    use HasFactory;

    protected $table = 'linea_trabajo';
    protected $primaryKey = 'id_linea_trabajo';
    public $timestamps = false;

    protected $fillable = [
        'id_unidad_ppto',
        'codigo_linea_trabajo',
        'nombre_linea_trabajo',
        'proposito_linea_trabajo',
        //'estado_unidad_ppto',
        'fecha_reg_linea_trabajo',
        'fecha_mod_linea_trabajo',
        'usuario_linea_trabajo',
        'ip_linea_trabajo',
    ];

    public function unidad_presupuestaria()
    {
        return $this->belongsTo('App\Models\UnidadPresupuestaria','id_unidad_ppto','id_unidad_ppto');
    }

    public function dependencias()
    {
        return $this->belongsToMany('App\Models\Dependencia', 'uplt_dependencia', 'id_linea_trabajo', 'id_dependencia')
            ->using('App\Models\UpltDependencia')
            ->withPivot([
                'id_uplt_dependencia',
                'id_dependencia',
                'id_linea_trabajo',
                'estado_uplt_dependencia',
            ]);
    }
}
