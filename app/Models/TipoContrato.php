<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoContrato extends Model
{
    use HasFactory;

    protected $table = 'tipo_contrato';
    protected $primaryKey = 'id_tipo_contrato';
    public $timestamps = false;

    protected $fillable = [
        'codigo_tipo_contrato',
        'nombre_tipo_contrato',
        'fecha_reg_tipo_contrato',
        'fecha_mod_tipo_contrato',
        'usuario_tipo_contrato',
        'ip_tipo_contrato'
    ];
}
