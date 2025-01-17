<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluacionPersonal extends Model
{
    use HasFactory;

    protected $table = 'evaluacion_personal';
    protected $primaryKey = 'id_evaluacion_personal';
    public $timestamps = false;

    protected $fillable = [
        'id_evaluacion_rendimiento',
        'id_periodo_evaluacion',
        'id_empleado',
        'id_dependencia',
        "id_estado_evaluacion_personal",
        'id_tipo_evaluacion_personal',
        'fecha_evaluacion_personal',
        'puntaje_evaluacion_personal',
        'fecha_inicio_evaluacion_personal',
        'fecha_fin_evaluacion_personal',
        'observacion_incidente_personal',
        'fecha_reg_evaluacion_personal',
        'fecha_mod_evaluacion_personal',
        'usuario_evaluacion_personal',
        'ip_evaluacion_personal',
    ];
    /**
     * Get the empleado that owns the EvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }
    /**
     * Get all of the detalle_evaluaciones_personal for the EvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalle_evaluaciones_personal(): HasMany
    {
        return $this->hasMany(DetalleEvaluacionPersonal::class, 'id_evaluacion_personal', 'id_evaluacion_personal');
    }
    /**
     * Get all of the incidentes_evaluacion for the EvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incidentes_evaluacion(): HasMany
    {
        return $this->hasMany(IncidenteEvaluacion::class, 'id_evaluacion_personal', 'id_evaluacion_personal');
    }
    /**
     * Get all of the plaza_evaluada for the EvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plaza_evaluada(): HasMany
    {
        return $this->hasMany(PlazaEvaluada::class, 'id_evaluacion_personal', 'id_evaluacion_personal');
    }
    /**
     * Get the evaluacion_rendimiento that owns the EvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evaluacion_rendimiento(): BelongsTo
    {
        return $this->belongsTo(EvaluacionRendimiento::class, 'id_evaluacion_rendimiento', 'id_evaluacion_rendimiento');
    }
    /**
     * Get the tipo_evaluacion that owns the EvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo_evaluacion_personal(): BelongsTo
    {
        return $this->belongsTo(TipoEvaluacionPersonal::class, 'id_tipo_evaluacion_personal', 'id_tipo_evaluacion_personal');
    }
    /**
     * Get the tipo_evaluacion that owns the EvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function periodo_evaluacion(): BelongsTo
    {
        return $this->belongsTo(PeriodoEvaluacion::class, 'id_periodo_evaluacion', 'id_periodo_evaluacion');
    }
    /**
     * Get the estado_evaluacion_personal that owns the EvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estado_evaluacion_personal(): BelongsTo
    {
        return $this->belongsTo(EstadoEvaluacionPersonal::class, 'id_estado_evaluacion_personal', 'id_estado_evaluacion_personal');
    }
}
