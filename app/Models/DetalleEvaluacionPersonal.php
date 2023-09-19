<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetalleEvaluacionPersonal extends Model
{
    use HasFactory;
    protected $table = 'detalle_evaluacion_personal';
    protected $primaryKey = 'id_detalle_eva_personal';
    public $timestamps = false;

    protected $fillable = [
        'id_evaluacion_personal',
        'id_cat_rendimiento',
        'id_rubrica_rendimiento',
        'fecha_reg_detalle_eva_personal',
        'fecha_mod_detalle_eva_personal',
        'usuario_detalle_eva_personal',
        'ip_detalle_eva_personal',
    ];
    /**
     * Get the evaluacion_personal that owns the DetalleEvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evaluacion_personal(): BelongsTo
    {
        return $this->belongsTo(EvaluacionPersonal::class, 'id_evaluacion_personal', 'id_evaluacion_personal');
    }
    /**
     * Get all of the categorias_rendimiento for the DetalleEvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    /**
     * Get the categoria_rendimiento that owns the DetalleEvaluacionPersonal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria_rendimiento(): BelongsTo
    {
        return $this->belongsTo(CategoriaRendimiento::class, 'id_cat_rendimiento', 'id_cat_rendimiento');
    }

    public function rubrica_rendimiento(): BelongsTo
    {
        return $this->belongsTo(RubricaRendimiento::class, 'id_rubrica_rendimiento', 'id_rubrica_rendimiento');
    }
}
