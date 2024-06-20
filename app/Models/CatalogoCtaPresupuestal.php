<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoCtaPresupuestal extends Model
{
    use HasFactory;

    protected $table = 'catalogo_cta_presupuestal';
    protected $primaryKey = 'id_ccta_presupuestal';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_af',
        'id_padre_ccta_presupuestal',
        'codigo_ccta_presupuestal',
        'nombre_ccta_presupuestal',
        'cuenta_gasto_ccta_presupuestal',
        'cuenta_inversion_ccta_presupuestal',
        'cuenta_existencia_ccta_presupuestal',
        'estado_ccta_presupuestal',
        'tesoreria_ccta_presupuestal',
        'activo_fijo_ccta_presupuestal',
        'compra_ccta_presupuestal',
        'descripcion_ccta_presupuestal',
    ];
}
