<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoCentroAtencion extends Model
{
    use HasFactory;
    protected $table = 'tipo_centro_atencion';
    protected $primaryKey = 'id_tipo_centro_atencion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_centro_atencion',
    ];
    /**
     * Get all of the centro_produccion for the TipoCentroAtencion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function centros_produccion(): HasMany
    {
        return $this->hasMany(CentroProduccion::class, 'id_tipo_centro_atencion', 'id_tipo_centro_atencion');
    }
    /**
     * Get all of the centro_atencion for the TipoCentroAtencion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function centros_atencion(): HasMany
    {
        return $this->hasMany(CentroAtencion::class, 'id_tipo_centro_atencion', 'id_tipo_centro_atencion');
    }
}
