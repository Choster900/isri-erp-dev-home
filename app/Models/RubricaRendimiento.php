<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RubricaRendimiento extends Model
{
    use HasFactory;
    protected $table = 'rubrica_rendimiento';
    protected $primaryKey = 'id_rubrica_rendimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_rubrica_rendimiento',
        'id_cat_rendimiento',
        'opcion_rubrica_rendimiento',
        'descripcion_rubrica_rendimiento',
        'puntaje_rubrica_rendimiento',
        'estado_rubrica_rendimiento',
        'fecha_reg_rubrica_rendimiento',
        'fecha_mod_rubrica_rendimiento',
        'usuario_rubrica_rendimiento',
        'ip_rubrica_rendimiento',
    ];
    /**
     * Get the categoria_rendimiento that owns the RubricaRendimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria_rendimiento(): BelongsTo
    {
        return $this->belongsTo(CategoriaRendimiento::class, 'id_cat_rendimiento', 'id_cat_rendimiento');
    }
}
