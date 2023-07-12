<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineaTrabajo extends Model
{
    use HasFactory;

    protected $table = 'linea_trabajo';
    protected $primaryKey = 'id_lt';
    public $timestamps = false;

    protected $fillable = [
        'id_up',
        'codigo_up_lt',
        'codigo_lt',
        'nombre_lt',
        'proposito_lt',
        'plaza_lt',
        //'estado_unidad_ppto',
        'fecha_reg_lt',
        'fecha_mod_lt',
        'usuario_lt',
        'ip_lt',
    ];

    public function actividades()
    {
        return $this->hasMany('App\Models\ActividadInstitucional','id_lt','id_lt');
    }

    public function unidad_presupuestaria()
    {
        return $this->belongsTo('App\Models\UnidadPresupuestaria','id_up','id_up');
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
