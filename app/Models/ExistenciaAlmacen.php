<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExistenciaAlmacen extends Model
{
    use HasFactory;
    protected $table = 'existencia_almacen';
    protected $primaryKey = 'id_existencia_almacen';
    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'id_proy_financiado',
        'cant_existencia_almacen',
        'costo_existencia_almacen',
        'fecha_reg_existencia_almacen',
        'fecha_mod_existencia_almacen',
        'usuario_existencia_almacen',
        'ip_existencia_almacen',
    ];
    /**
     * Get all of the detalle_existencia_almacen for the ExistenciaAlmacen
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalle_existencia_almacen(): HasMany
    {
        return $this->hasMany(DetalleExistenciaAlmacen::class, 'id_existencia_almacen', 'id_existencia_almacen');
    }

    /**
     * Get the productos that owns the ExistenciaAlmacen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productos(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

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
     * Get the ProyectoFinanciado model associated with this instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto_financiado()
    {
        return $this->belongsTo(ProyectoFinanciado::class, 'id_proy_financiado', 'id_proy_financiado');
    }
}
