<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReciboIngreso extends Model
{
    use HasFactory;

    protected $table = 'recibo_ingreso';
    protected $primaryKey = 'id_recibo_ingreso';
    public $timestamps = false;

    protected $fillable = [
        'id_ctrl_ingreso',
        'id_proy_financiado',
        'id_ccta_presupuestal',
        'id_empleado_tesoreria',
        'fecha_recibo_ingreso',
        'numero_recibo_ingreso',
        'doc_identidad_recibo_ingreso',
        'direccion_cliente_recibo_ingreso',
        'cliente_recibo_ingreso',
        'monto_recibo_ingreso',
        'descripcion_recibo_ingreso',
        'estado_recibo_ingreso',
        'fecha_reg_recibo_ingreso',
        'fecha_mod_recibo_ingreso',
        'ip_recibo_ingreso',
        'usuario_recibo_ingreso'
    ];

    public function detalles()
    {
        return $this->hasMany('App\Models\DetalleReciboIngreso','id_recibo_ingreso','id_recibo_ingreso');
    }

    public function cuenta_presupuestal()
    {
        return $this->belongsTo('App\Models\CuentaPresupuestal','id_ccta_presupuestal','id_ccta_presupuestal');
    }

    public function proyecto_financiado()
    {
        return $this->belongsTo('App\Models\ProyectoFinanciado','id_proy_financiado','id_proy_financiado');
    }

    public function empleado_tesoreria()
    {
        return $this->belongsTo('App\Models\EmpleadoTesoreria','id_empleado_tesoreria','id_empleado_tesoreria');
    }
}
