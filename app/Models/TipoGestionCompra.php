<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoGestionCompra extends Model
{
    use HasFactory;

    protected $table = 'tipo_gestion_compra';
    protected $primaryKey = 'id_tipo_gestion_compra';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_gestion_compra',
        'nombre_tipo_gestion_compra',
        'codigo_tipo_gestion_compra',
        'estado_tipo_gestion_compra',
        'fecha_reg_tipo_gestion_compra',
        'fecha_mod_tipo_gestion_compra',
        'usuario_tipo_gestion_compra',
        'ip_tipo_gestion_compra',
    ];
}
