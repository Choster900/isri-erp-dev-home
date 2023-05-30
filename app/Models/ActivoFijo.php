<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivoFijo extends Model
{
    use HasFactory;

    protected $table = 'activo_fijo';
    protected $primaryKey = 'id_af';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_af',
        'id_estado_af',
        'id_modelo',
        'id_vehiculo',
        'id_bien_especifico',
        'id_proveedor',
        'id_proy_financiado',
        'id_bien_depreciable',
        'codigo_af',
        'codigo_antiguo_af',
        'nombre_af',
        'serie_af',
        'fecha_adquisicion_af',
        'fecha_depreciacion_af',
        'valor_adquisicion_af',
        'valor_libro_af',
        'valor_residual_af',
        'vida_util_af',
        'doc_adquisicion_af',
        'observacion_af',
        'estado_af',
        'fecha_reg_af',
        'fecha_mod_af',
        'usuario_af',
        'ip_af'
    ];

    public function bien_especifico()
    {
        return $this->belongsTo('App\Models\BienEspecifico','id_bien_especifico','id_bien_especifico');
    }
    public function modelo()
    {
        return $this->belongsTo('App\Models\Modelo','id_modelo','id_modelo');
    }
}
