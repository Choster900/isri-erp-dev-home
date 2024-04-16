<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleExistenciaAlmacen extends Model
{
    use HasFactory;
    protected $table = 'detalle_existencia_almacen';
    protected $primaryKey = 'id_det_existencia_almacen';
    public $timestamps = false;

    protected $fillable = [
        'id_lt',
        'id_existencia_almacen',
        'id_marca',
        'id_centro_atencion',
        'cant_det_existencia_almacen',
        'fecha_reg_det_existencia_almacen',
        'fecha_vcto_det_existencia_almacen',
        'fecha_mod_det_existencia_almacen',
        'usuario_det_existencia_almacen',
        'ip_det_existencia_almacen',
    ];

    /**
     * Get the existencia_almacen that owns the DetalleExistenciaAlmacen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function existencia_almacen(): BelongsTo
    {
        return $this->belongsTo(ExistenciaAlmacen::class, 'id_existencia_almacen', 'id_existencia_almacen');
    }
    /**
     * Get the marca that owns the DetalleExistenciaAlmacen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'id_marca', 'id_marca');
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
     * Get the LineaTrabajo model associated with this instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function linea_trabajo()
    {
        return $this->belongsTo(LineaTrabajo::class, 'id_lt', 'id_lt');
    }
}
