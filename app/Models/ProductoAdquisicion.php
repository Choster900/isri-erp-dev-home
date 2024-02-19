<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoAdquisicion extends Model
{
    use HasFactory;

    protected $table = 'producto_adquisicion';
    protected $primaryKey = 'id_prod_adquisicion';
    public $timestamps = false;

    protected $fillable = [
        'id_det_doc_adquisicion',
        'id_lt',
        'id_centro_atencion',
        'id_producto',
        'cant_prod_adquisicion',
        'costo_prod_adquisicion',
        'descripcion_prod_adquisicion',
        'estado_prod_adquisicion',
        'fecha_reg_prod_adquisicion',
        'fecha_mod_prod_adquisicion',
        'usuario_prod_adquisicion',
        'ip_prod_adquisicion',
    ];
}
