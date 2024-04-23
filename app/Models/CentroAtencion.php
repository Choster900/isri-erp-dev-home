<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentroAtencion extends Model
{
    use HasFactory;

    protected $table = 'centro_atencion';
    protected $primaryKey = 'id_centro_atencion';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_centro_atencion',
        'nombre_centro_atencion',
        'codigo_centro_atencion',
        'pagaduria_centro_atencion',
        'telefono_centro_atencion',
        'email_centro_atencion',
        'direccion_centro_atencion',
        'estado_centro_atencion',
        'fecha_reg_centro_atencion',
        'fecha_mod_centro_atencion',
        'usuario_centro_atencion',
        'ip_centro_atencion'
    ];

    /**
     * Get all of the dependencias for the CentroAtencion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dependencias(): HasMany
    {
        return $this->hasMany(Dependencia::class, 'id_centro_atencion', 'id_centro_atencion');
    }
    /**
     * Get the tipo_centro_atencion that owns the CentroAtencion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo_centro_atencion(): BelongsTo
    {
        return $this->belongsTo(TipoCentroAtencion::class, 'id_tipo_centro_atencion', 'id_tipo_centro_atencion');
    }
    /**
     * Get all of the asignacion_centro_produccion for the CentroAtencion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function asignacion_centro_produccion(): HasMany
    {
        return $this->hasMany(AsignacionCentroProduccion::class, 'id_centro_atencion', 'id_centro_atencion');
    }
}
