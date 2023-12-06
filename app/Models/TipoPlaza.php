<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoPlaza extends Model
{
    use HasFactory;

    protected $table = 'tipo_plaza';
    protected $primaryKey = 'id_tipo_plaza';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_plaza',
        'estado_tipo_plaza',
    ];
    /**
     * Get all of the plazas for the TipoPlaza
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plazas(): HasMany
    {
        return $this->hasMany(Plaza::class, 'id_tipo_plaza', 'id_tipo_plaza');
    }
    /**
     * Get all of the evaluaciones_rendimientos for the TipoPlaza
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evaluaciones_rendimientos(): HasMany
    {
        return $this->hasMany(EvaluacionRendimiento::class, 'id_tipo_plaza', 'id_tipo_plaza');
    }
}