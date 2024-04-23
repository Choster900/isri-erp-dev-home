<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentroProduccion extends Model
{
    use HasFactory;

    protected $table = 'centro_produccion';
    protected $primaryKey = 'id_centro_produccion';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_centro_atencion',
        'nombre_centro_produccion',
        'sigla_centro_produccion',
        'codigo_centro_produccion',
        'codigo2_centro_produccion',
        'estado_centro_produccion',
    ];

    /**
     * Get all of the detalles_requerimientos for the CentroProduccion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalles_requerimientos(): HasMany
    {
        return $this->hasMany(DetalleRequerimiento::class, 'id_centro_produccion', 'id_centro_produccion');
    }

}
