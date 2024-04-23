<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    use HasFactory;

    protected $table = 'kardex';
    protected $primaryKey = 'id_kardex';
    public $timestamps = false;

    protected $fillable = [
        'id_recepcion_pedido',
        'id_proy_financiado',
        'id_tipo_mov_kardex',
        'id_requerimiento',
        'id_tipo_req',
        'fecha_kardex',
        'observacion_kardex',
        'fecha_reg_kardex',
        'fecha_mod_kardex',
        'usuario_kardex',
        'ip_kardex',
    ];
}
