<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'id_proceso_compra',
        'id_catalogo_unspsc',
        'id_ccta_nicsp',
        'id_catalogo_perc',
        'id_ccta_presupuestal',
        'id_sub_almacen',
        'id_unidad_medida',
        'nombre_producto',
        'codigo_producto',
        'descripcion_producto',
        'precio_producto',
        'basico_producto',
        'perecedero_producto',
        'estado_producto',
        'critico_producto',
        'fraccionado_producto',
        'fecha_reg_producto',
        'fecha_mod_producto',
        'usuario_producto',
        'ip_producto',
        'nombre_completo_producto'
    ];

    public function unidad_medida()
    {
        return $this->belongsTo('App\Models\UnidadMedida','id_unidad_medida','id_unidad_medida');
    }

    public function catalogo_unspsc()
    {
        return $this->belongsTo('App\Models\CatalogoUnspsc','id_catalogo_unspsc','id_catalogo_unspsc');
    }

    public function catalogo_cta_presupuestal()
    {
        return $this->belongsTo('App\Models\CatalogoCtaPresupuestal','id_ccta_presupuestal','id_ccta_presupuestal');
    }
}
