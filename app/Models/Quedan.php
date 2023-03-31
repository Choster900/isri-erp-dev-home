<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quedan extends Model
{
    use HasFactory;
    protected $table = 'quedan';
    protected $primaryKey = 'id_quedan';
    public $timestamps = false;
    protected $fillable = [
        'id_estado_quedan',
        'id_prioridad_pago',
        'id_requerimiento_pago',
        'id_tesorero',
        'id_proy_financiado',
        'id_serie_retencion_iva',
        'id_proveedor',
        'numero_quedan',
        'numero_retencion_iva_quedan',
        'numero_compromiso_ppto_quedan',
        'fecha_emision_quedan',
        'fecha_retencion_iva_quedan',
        'fecha_pago_quedan',
        'monto_liquido_quedan',
        'monto_iva_quedan',
        'monto_isr_quedan',
        'descripcion_quedan',
        'estado_quedan',
        'fecha_reg_quedan',
        'fecha_mod_quedan',
        'usuario_quedan',
        'ip_quedan',
    ];


    public function detalle_quedan()
    { //(FOREING KEY, PRIMARY KEY)
        return $this->hasMany(DetalleQuedan::class, "id_quedan", "id_quedan");
    }
    public function proveedor()
    { //(FOREING KEY, PRIMARY KEY)
        return $this->belongsTo(Proveedor::class, "id_proveedor", "id_proveedor");
    }

}