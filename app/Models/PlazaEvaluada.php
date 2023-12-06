<?php

namespace App\Models;

use App\Http\Requests\RRHH\PlazaAsignadaRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlazaEvaluada extends Model
{
    use HasFactory;

    protected $table = 'plaza_evaluada';
    protected $primaryKey = 'id_plaza_evaluada';
    public $timestamps = false;

    protected $fillable = [
        'id_plaza_asignada',
        'id_evaluacion_personal',
        'estado_plaza_evaluada',
        'fecha_reg_plaza_evaluada',
        'fecha_mod_plaza_evaluada',
        'usuario_plaza_evaluada',
        'ip_plaza_evaluada',
    ];

    /**
     * Get the plazas_asignadas that owns the PlazaEvaluada
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plazas_asignadas(): BelongsTo
    {
        return $this->belongsTo(PlazaAsignada::class, 'id_plaza_asignada', 'id_plaza_asignada');
    }
    /**
     * Get the evaluaciones_personales that owns the PlazaEvaluada
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evaluaciones_personales(): BelongsTo
    {
        return $this->belongsTo(EvaluacionPersonal::class, 'id_evaluacion_personal', 'id_evaluacion_personal');
    }
}
