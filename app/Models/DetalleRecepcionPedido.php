<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleRecepcionPedido extends Model
{
    use HasFactory;

    protected $table = 'detalle_recepcion_pedido';
    protected $primaryKey = 'id_det_recepcion_pedido';
    public $timestamps = false;

    protected $fillable = [
        'id_det_doc_adquisicion',
        'id_centro_atencion',
        'id_producto',
        'id_marca',
        'id_prod_adquisicion',
        'id_recepcion_pedido',
        'fecha_vcto_det_recepcion_pedido',
        'cant_det_recepcion_pedido',
        'costo_det_recepcion_pedido',
        'estado_det_recepcion_pedido',
        'fecha_reg_det_recepcion_pedido',
        'fecha_mod_det_recepcion_pedido',
        'usuario_det_recepcion_pedido',
        'ip_det_recepcion_pedido',
    ];

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto', 'id_producto', 'id_producto');
    }

    public function producto_adquisicion()
    {
        return $this->belongsTo('App\Models\ProductoAdquisicion', 'id_prod_adquisicion', 'id_prod_adquisicion');
    }

    public function centro_atencion()
    {
        return $this->belongsTo('App\Models\CentroAtencion', 'id_centro_atencion', 'id_centro_atencion');
    }
}
