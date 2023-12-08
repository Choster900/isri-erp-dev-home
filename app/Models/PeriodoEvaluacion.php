<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodoEvaluacion extends Model
{
    use HasFactory;
    protected $table = 'periodo_evaluacion';
    protected $primaryKey = 'id_periodo_evaluacion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_periodo_evaluacion',
    ];
    /**
     * Get all of the evaluaciones_personales for the PeriodoEvaluacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evaluaciones_personales(): HasMany
    {
        return $this->hasMany(EvaluacionPersonal::class, 'id_periodo_evaluacion', 'id_periodo_evaluacion');
    }
}
