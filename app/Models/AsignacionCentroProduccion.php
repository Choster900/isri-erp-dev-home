<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionCentroProduccion extends Model
{
    use HasFactory;
    protected $table = 'asignacion_centro_produccion';
    protected $primaryKey = 'id_asignacion_centro_prod';
    public $timestamps = false;
    protected $fillable = [
        'id_centro_produccion',
        'id_centro_atencion',
        'estado_asignacion_centro_prod',
    ];
    /**
     * Get the centro_atencion that owns the AsignacionCentroProduccion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function centro_atencion(): BelongsTo
    {
        return $this->belongsTo(CentroAtencion::class, 'id_centro_atencion', 'id_centro_atencion');
    }
    /**
     * Get the centro_produccion that owns the AsignacionCentroProduccion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function centro_produccion(): BelongsTo
    {
        return $this->belongsTo(CentroProduccion::class, 'id_centro_produccion', 'id_centro_produccion');
    }
}
