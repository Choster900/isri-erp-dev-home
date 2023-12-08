<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluacionRendimiento extends Model
{
    use HasFactory;

    protected $table = 'evaluacion_rendimiento';
    protected $primaryKey = 'id_evaluacion_rendimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_plaza',
        'codigo_evaluacion_rendimiento',
        'nombre_evaluacion_rendimiento',
        'version_evaluacion_rendimiento',
        'estado_evaluacion_rendimiento',
        'fecha_reg_evaluacion_rendimiento',
        'fecha_mod_evaluacion_rendimiento',
        'usuario_evaluacion_rendimiento',
        'ip_evaluacion_rendimiento',
    ];

    /**
     * Get all of the tablas_rendimiento for the EvaluacionRendimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tablas_rendimiento(): HasMany
    {
        return $this->hasMany(TablaRendimiento::class, 'id_evaluacion_rendimiento', 'id_evaluacion_rendimiento');
    }
    /**
     * Get all of the categorias_rendimiento for the EvaluacionRendimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categorias_rendimiento(): HasMany
    {
        return $this->hasMany(CategoriaRendimiento::class, 'id_evaluacion_rendimiento', 'id_evaluacion_rendimiento');
    }
    /**
     * Get the tipo_plaza that owns the EvaluacionRendimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo_plaza(): BelongsTo
    {
        return $this->belongsTo(TipoPlaza::class, 'id_tipo_plaza', 'id_tipo_plaza');
    }
    /**
     * Get all of the evaluaciones_personal for the EvaluacionRendimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evaluaciones_personal(): HasMany
    {
        return $this->hasMany(EvaluacionPersonal::class, 'id_evaluacion_rendimiento', 'id_evaluacion_rendimiento');
    }
}
