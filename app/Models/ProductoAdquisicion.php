<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoAdquisicion extends Model
{
    use HasFactory;

    protected $table = 'producto_adquisicion';
    protected $primaryKey = 'id_prod_adquisicion';
    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'id_det_doc_adquisicion',
        'id_marca',
        'id_lt',
        'id_centro_atencion',
        'cant_prod_adquisicion',
        'costo_prod_adquisicion',
        'descripcion_prod_adquisicion',
        'estado_prod_adquisicion',
        'fecha_reg_prod_adquisicion',
        'fecha_mod_prod_adquisicion',
        'usuario_prod_adquisicion',
        'ip_prod_adquisicion',
        'cant_ene_prod_adquisicion',
        'cant_feb_prod_adquisicion',
        'cant_mar_prod_adquisicion',
        'cant_abr_prod_adquisicion',
        'cant_may_prod_adquisicion',
        'cant_jun_prod_adquisicion',
        'cant_jul_prod_adquisicion',
        'cant_ago_prod_adquisicion',
        'cant_sept_prod_adquisicion',
        'cant_oct_prod_adquisicion',
        'cant_nov_prod_adquisicion',
        'cant_dic_prod_adquisicion',
    ];
    /**
     * Get the marca that owns the ProductoAdquisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'id_marca', 'id_marca');
    }
    /**
     * Get the producto that owns the ProductoAdquisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
    /**
     * Get the linea_trabajo that owns the ProductoAdquisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function linea_trabajo(): BelongsTo
    {
        return $this->belongsTo(LineaTrabajo::class, 'id_lt', 'id_lt');
    }
    /**
     * Get the centro_atencion that owns the ProductoAdquisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function centro_atencion(): BelongsTo
    {
        return $this->belongsTo(CentroAtencion::class, 'id_centro_atencion', 'id_centro_atencion');
    }
}
