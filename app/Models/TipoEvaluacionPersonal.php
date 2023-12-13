<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEvaluacionPersonal extends Model
{
    use HasFactory;
    protected $table = 'tipo_evaluacion_personal';
    protected $primaryKey = 'id_tipo_evaluacion_personal';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_evaluacion_personal',
    ];
    /**
     * Get all of the evaluaciones_personales for the PeriodoEvaluacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evaluaciones_personales(): HasMany
    {
        return $this->hasMany(EvaluacionPersonal::class, 'id_tipo_evaluacion_personal', 'id_tipo_evaluacion_personal');
    }
}
