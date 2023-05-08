<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoIngreso extends Model
{
    use HasFactory;

    protected $table = 'concepto_ingreso';
    protected $primaryKey = 'id_concepto_ingreso';
    public $timestamps = false;

    protected $fillable = [
        'id_ccta_presupuestal',
        'id_dependencia',
        'id_proy_financiado',
        'nombre_concepto_ingreso',
        'detalle_concepto_ingreso',
        'fecha_reg_concepto_ingreso',
        'fecha_mod_concepto_ingreso',
        'ip_concepto_ingreso',
        'usuario_concepto_ingreso'
    ];

    public function dependencia()
    {
        return $this->belongsTo('App\Models\Dependencia','id_dependencia','id_dependencia');
    }

    public function cuenta_presupuestal()
    {
        return $this->belongsTo('App\Models\CuentaPresupuestal','id_ccta_presupuestal','id_ccta_presupuestal');
    }
}
