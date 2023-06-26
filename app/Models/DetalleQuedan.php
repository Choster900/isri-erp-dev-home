<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleQuedan extends Model
{
    use HasFactory;
    protected $table = 'detalle_quedan';
    protected $primaryKey = 'id_det_quedan';
    public $timestamps = false;
    protected $fillable = [
        'id_det_quedan',
        'id_dependencia',
        'id_quedan',
        'numero_acta_det_quedan',
        'numero_factura_det_quedan',
        'fecha_factura_det_quedan',
        'producto_factura_det_quedan',
        'servicio_factura_det_quedan',
        'ajuste_producto_factura_det_quedan',
        'ajuste_servicio_factura_det_quedan',
        'iva_factura_det_quedan',
        'isr_factura_det_quedan',
        'descripcion_det_quedan',
        'justificacion_det_quedan',
        'fecha_reg_det_quedan',
        'fecha_mod_det_quedan',
        'usuario_det_quedan',
        'ip_det_quedan',
    ];


    public function detalle_quedan()
    {
        return $this->belongsTo(Quedan::class, "id_quedan", "id_quedan");
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, "id_dependencia", "id_dependencia");
    }


}