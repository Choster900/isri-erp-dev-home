<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleRequerimiento extends Model
{
    use HasFactory;
    protected $table = 'detalle_requerimiento';
    protected $primaryKey = 'id_det_requerimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'id_centro_produccion',
        'id_requerimiento',
        'id_marca',
        'cant_det_requerimiento',
        'costo_det_requerimiento',
        'fecha_vcto_det_requerimiento',
        'estado_det_requerimiento',
        'fecha_reg_det_requerimiento',
        'fecha_mod_det_requerimiento',
        'usuario_det_requerimiento',
        'ip_det_requerimiento',
    ];
    /**
     * Get the Requerimiento that owns the DetalleRequerimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Requerimiento(): BelongsTo
    {
        return $this->belongsTo(Requerimiento::class, 'id_requerimiento', 'id_requerimiento');
    }
    /**
     * Get the centro_produccion that owns the DetalleRequerimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function centro_produccion(): BelongsTo
    {
        return $this->belongsTo(CentroProduccion::class, 'id_centro_produccion', 'id_centro_produccion');
    }
    /**
     * Get the producto that owns the DetalleRequerimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
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
}
