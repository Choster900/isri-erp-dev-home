<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleKardex extends Model
{
    use HasFactory;

    protected $table = 'detalle_kardex';
    protected $primaryKey = 'id_detalle_kardex';
    public $timestamps = false;

    protected $fillable = [
        'id_kardex',
        'id_producto',
        'id_lt',
        'id_centro_atencion',
        'id_marca',
        'cant_det_kardex',
        'costo_det_kardex',
        'fecha_vencimiento_det_kardex',
        'fecha_reg_det_kardex',
        'fecha_mod_det_kardex',
        'usuario_det_kardex',
        'ip_det_kardex',
    ];
}
