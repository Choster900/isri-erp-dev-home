<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidenteEvaluacion extends Model
{
    use HasFactory;
    protected $table = 'incidente_evaluacion';
    protected $primaryKey = 'id_incidente_evaluacion';
    public $timestamps = false;

    protected $fillable = [
        'id_evaluacion_personal',
        'id_cat_rendimiento',
        'resultado_incidente_evaluacion',
        'estado_incidente_evaluacion',
        'comentario_incidente_evaluacion',
        'fecha_reg_incidente_evaluacion',
        'fecha_mod_incidente_evaluacion',
        'usuario_incidente_evaluacion',
        'ip_incidente_evaluacion',
    ];

    /**
     * Get the evaluacion_personal that owns the IncidenteEvaluacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evaluacion_personal(): BelongsTo
    {
        return $this->belongsTo(EvaluacionPersonal::class, 'id_evaluacion_personal', 'id_evaluacion_personal');
    }
    /**
     * Get the categoria_rendimiento that owns the IncidenteEvaluacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria_rendimiento(): BelongsTo
    {
        return $this->belongsTo(CategoriaRendimiento::class, 'id_cat_rendimiento', 'id_cat_rendimiento');
    }
}
