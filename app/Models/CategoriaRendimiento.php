<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaRendimiento extends Model
{
    use HasFactory;

    protected $table = 'categoria_rendimiento';
    protected $primaryKey = 'id_cat_rendimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_cat_rendimiento',
        'id_evaluacion_rendimiento',
        'nombre_cat_rendimiento',
        'descripcion_cat_rendimiento',
        'estado_cat_rendimiento',
        'fecha_reg_cat_rendimiento',
        'fecha_mod_cat_rendimiento',
        'usuario_cat_rendimiento',
        'ip_cat_rendimiento',
    ];
    /**
     * Get all of the rubricas_rendimiento for the CategoriaRendimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rubricas_rendimiento(): HasMany
    {
        return $this->hasMany(RubricaRendimiento::class, 'id_cat_rendimiento', 'id_cat_rendimiento');
    }
    /**
     * Get the evaluacion_rendimiento that owns the CategoriaRendimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evaluacion_rendimiento(): BelongsTo
    {
        return $this->belongsTo(EvaluacionRendimiento::class, 'id_evaluacion_rendimiento', 'id_evaluacion_rendimiento');
    }
    /**
     * Get the detalle_evaluacion_personal that owns the CategoriaRendimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detalle_evaluacion_personal(): HasMany
    {
        return $this->HasMany(DetalleEvaluacionPersonal::class, 'id_cat_rendimiento', 'id_cat_rendimiento');
    }
}
