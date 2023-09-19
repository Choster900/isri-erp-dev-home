<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TablaRendimiento extends Model
{
    use HasFactory;
    protected $table = 'tabla_rendimiento';
    protected $primaryKey = 'id_tabla_rendimiento';
    public $timestamps = false;
    protected $fillable = [
        'id_evaluacion_rendimiento',
        'nombre_tabla_rendimiento',
        'rango_inferior_tabla_rendimiento',
        'rango_superior_tabla_rendimiento',
        'estado_tabla_rendimiento',
        'fecha_reg_tabla_rendimiento',
        'fecha_mod_tabla_rendimiento',
        'usuario_tabla_rendimiento',
        'ip_tabla_rendimiento',
    ];
    /**
     * Get the evaluacion_rendimiento that owns the TablaRendimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evaluacion_rendimiento(): BelongsTo
    {
        return $this->belongsTo(EvaluacionRendimiento::class, 'id_evaluacion_rendimiento', 'id_evaluacion_rendimiento');
    }
}
