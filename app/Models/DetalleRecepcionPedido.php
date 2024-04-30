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
        'id_lt',
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

    /**
     * Get the Producto model associated with this instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
    /**
     * Get the ProductoAdquisicion model associated with this instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto_adquisicion()
    {
        return $this->belongsTo(ProductoAdquisicion::class, 'id_prod_adquisicion', 'id_prod_adquisicion');
    }
    /**
     * Get the CentroAtencion model associated with this instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function centro_atencion()
    {
        return $this->belongsTo(CentroAtencion::class, 'id_centro_atencion', 'id_centro_atencion');
    }
    /**
     * Get the Marca model associated with this instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marca', 'id_marca');
    }
    /**
     * Get the LineaTrabajo model associated with this instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function linea_trabajo()
    {
        return $this->belongsTo(LineaTrabajo::class, 'id_lt', 'id_lt');
    }
}
