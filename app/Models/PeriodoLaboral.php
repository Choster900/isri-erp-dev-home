<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoLaboral extends Model
{
    use HasFactory;

    protected $table = 'periodo_laboral';
    protected $primaryKey = 'id_periodo_laboral';
    public $timestamps = false;

    protected $fillable = [
        'id_motivo_desvinculo_laboral',
        'id_empleado',
        'observacion_periodo_laboral',
        'fecha_contratacion_periodo_laboral',
        'fecha_desvinculo_periodo_laboral',
        'estado_periodo_laboral',
        'compensacion_periodo_laboral',
        'fecha_reg_periodo_laboral',
        'fecha_mod_periodo_laboral',
        'usuario_periodo_laboral',
        'ip_periodo_laboral',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }
    public function motivo_desvinculo_laboral()
    {
        return $this->belongsTo(MotivoDesvinculoLaboral::class, 'id_motivo_desvinculo_laboral', 'id_motivo_desvinculo_laboral');
    }
}
