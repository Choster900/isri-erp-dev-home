<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePlaza extends Model
{
    use HasFactory;

    protected $table = 'detalle_plaza';
    protected $primaryKey = 'id_det_plaza';
    public $timestamps = false;

    protected $fillable = [
        'id_proy_financiado',
        'id_tipo_contrato',
        'id_actividad_institucional',
        'id_plaza',
        'id_estado_plaza',
        'codigo_det_plaza',
        'estado_det_plaza',
        'fecha_reg_det_plaza',
        'fecha_mod_det_plaza',
        'usuario_det_plaza',
        'ip_det_plaza',
    ];

    public function actividad_institucional()
    {
        return $this->belongsTo('App\Models\ActividadInstitucional','id_actividad_institucional','id_actividad_institucional');
    }
}
