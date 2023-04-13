<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoFinanciado extends Model
{
    use HasFactory;

    protected $table = 'proyecto_financiado';
    protected $primaryKey = 'id_proy_financiado';
    public $timestamps = false;

    protected $fillable = [
        'id_fuente_fmto',
        'codigo_proy_financiado',
        'nombre_proy_financiado',
        'estado_proy_financiado',
        'fecha_reg_proy_financiado',
        'fecha_mod_proy_financiado',
        'ip_proy_financiado',
        'usuario_proy_financiado'
    ];
}
