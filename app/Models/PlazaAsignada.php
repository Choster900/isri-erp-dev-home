<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlazaAsignada extends Model
{
    use HasFactory;

    protected $table = 'plaza_asignada';
    protected $primaryKey = 'id_plaza_asignada';
    public $timestamps = false;

    protected $fillable = [
        'id_empleado',
        'id_lt',
        'id_dependencia',
        'id_centro_atencion',
        'id_det_plaza',
        'id_centro_atencion',
        'id_motivo_desvinculo_laboral',
        'contrato_plaza_asignada',
        'salario_plaza_asignada',
        'partida_plaza_asignada',
        'subpartida_plaza_asignada',
        'fecha_plaza_asignada',
        'fecha_renuncia_plaza_asignada',
        'estado_plaza_asignada',
        'fecha_reg_plaza_asignada',
        'fecha_mod_plaza_asignada',
        'usuario_plaza_asignada',
        'ip_plaza_asignada',
    ];

    public function detalle_plaza()
    {
        return $this->belongsTo(DetallePlaza::class, 'id_det_plaza', 'id_det_plaza');
    }
    public function empleado()
    {
        return $this->belongsTo('App\Models\Empleado', 'id_empleado', 'id_empleado');
    }
    /**
     * The function "dependencia" returns a relationship between the current model and the
     * "Dependencia" model.
     */
    public function dependencia()
    {
        return $this->belongsTo('App\Models\Dependencia', 'id_dependencia', 'id_dependencia');
    }
    /**
     * The function "centro_atencion" returns a relationship between the current model and the
     * "CentroAtencion" model.
     */
    public function centro_atencion()
    {
        return $this->belongsTo(CentroAtencion::class, 'id_centro_atencion', 'id_centro_atencion');
    }

    public function motivo_desvinculo_laboral()
    {
        return $this->belongsTo(MotivoDesvinculoLaboral::class, 'id_motivo_desvinculo_laboral', 'id_motivo_desvinculo_laboral');
    }
    /**
     * Get all of the plazaEvaluada for the PlazaAsignada
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plaza_evaluada(): HasMany
    {
        return $this->hasMany(PlazaEvaluada::class, 'id_plaza_asignada', 'id_plaza_asignada');
    }

    /**
     * Accesores para formatear fechas
     */
    public function getFechaPlazaAsignadaFormateadaAttribute()
    {
        return $this->attributes['fecha_plaza_asignada'] ? date('d/m/Y', strtotime($this->attributes['fecha_plaza_asignada'])) : null;
    }

    public function getFechaRenunciaPlazaAsignadaFormateadaAttribute()
    {
        return $this->attributes['fecha_renuncia_plaza_asignada'] ? date('d/m/Y', strtotime($this->attributes['fecha_renuncia_plaza_asignada'])) : null;
    }

}
