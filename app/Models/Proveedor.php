<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'proveedor';
    protected $primaryKey = 'id_proveedor';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_contribuyente',
        'id_municipio',
        'id_sujeto_retencion',
        'razon_social_proveedor',
        'nombre_comercial_proveedor',
        'nrc_proveedor',
        'dui_proveedor',
        'nit_proveedor',
        'contacto_proveedor',
        'telefono1_proveedor',
        'telefono2_proveedor',
        'direccion_proveedor',
        'estado_proveedor',
        'fecha_reg_proveedor',
        'fecha_mod_proveedor',
        'usuario_proveedor',
        'ip_proveedor',
    ];

    public function sujeto_retencion()
    {
        return $this->belongsTo(SujetoRetencion::class,"id_sujeto_retencion","id_sujeto_retencion");
    }
}