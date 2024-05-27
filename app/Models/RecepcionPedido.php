<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecepcionPedido extends Model
{
    use HasFactory;

    protected $table = 'recepcion_pedido';
    protected $primaryKey = 'id_recepcion_pedido';
    public $timestamps = false;

    protected $fillable = [
        'id_det_doc_adquisicion',
        'id_proy_financiado',
        'id_estado_recepcion_pedido',
        'id_proveedor',
        'id_empleado', //Administrador de contrato
        'emp_id_empleado', //Guardaalmacen
        'factura_recepcion_pedido',
        'monto_recepcion_pedido',
        'representante_prov_recepcion_pedido',
        'fecha_recepcion_pedido',
        'acta_recepcion_pedido',
        'incumple_acuerdo_recepcion_pedido',
        'incumplimiento_recepcion_pedido',
        'observacion_recepcion_pedido',
        'fecha_reg_recepcion_pedido',
        'fecha_mod_recepcion_pedido',
        'usuario_recepcion_pedido',
        'ip_recepcion_pedido',
        'id_mes'
    ];

    public function detalle_recepcion()
    {
        return $this->hasMany('App\Models\DetalleRecepcionPedido', 'id_recepcion_pedido', 'id_recepcion_pedido');
    }

    public function det_doc_adquisicion()
    {
        return $this->belongsTo('App\Models\DetDocumentoAdquisicion','id_det_doc_adquisicion','id_det_doc_adquisicion');
    }

    public function estado_recepcion()
    {
        return $this->hasOne('App\Models\EstadoRecepcionPedido','id_estado_recepcion_pedido','id_estado_recepcion_pedido');
    }

    public function administrador_contrato()
    {
        return $this->belongsTo('App\Models\Empleado','id_empleado','id_empleado');
    }

    public function guarda_almacen()
    {
        return $this->belongsTo('App\Models\Empleado','emp_id_empleado','id_empleado');
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Models\Proveedor','id_proveedor','id_proveedor');
    }

    public function fuente_financiamiento()
    {
        return $this->belongsTo('App\Models\ProyectoFinanciado','id_proy_financiado','id_proy_financiado');
    }
}
