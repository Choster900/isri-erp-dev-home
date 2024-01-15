<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoEvaluacionPersonal extends Model
{
    use HasFactory;

    protected $table = 'estado_evaluacion_personal';
    protected $primaryKey = 'id_estado_evaluacion_personal';
    public $timestamps = false;

    protected $fillable = [
        'nombre_estado_evaluacion_personal',
        'descripcion_estado_evaluacion_personal',
    ];

    /**
     * Get all of the evaluaciones_personales for the EstadoEvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evaluaciones_personales(): HasMany
    {
        return $this->hasMany(EvaluacionPersonal::class, 'id_estado_evaluacion_personal', 'id_estado_evaluacion_personal');
    }
}
