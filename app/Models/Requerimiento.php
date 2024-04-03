<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requerimiento extends Model
{
    use HasFactory;
    protected $table = 'requerimiento';
    protected $primaryKey = 'id_requerimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_lt',
        'id_centro_atencion', //origin
        'cen_id_centro_atencion', //destination
        'id_motivo_ajuste',
        'req_id_requerimiento', //self-reference
        'id_proy_financiado',
        'id_estado_req',
        'id_tipo_req', //requerimiento, ajuste o traslado
        'num_requerimiento',
        'cant_personal_requerimiento',
        'fecha_requerimiento',
        'observacion_requerimiento',
        'fecha_reg_requerimiento',
        'fecha_mod_requerimiento',
        'usuario_requerimiento',
        'ip_requerimiento',
    ];
    /**
     * Get all of the detalle_requerimiento for the Requerimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalles_requerimiento(): HasMany
    {
        return $this->hasMany(DetalleRequerimiento::class, 'id_requerimiento', 'id_requerimiento');
    }
    /**
     * Get the centro_atencion that owns the Requerimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function centro_atencion(): BelongsTo
    {
        return $this->belongsTo(CentroAtencion::class, 'id_centro_atencion', 'id_centro_atencion');
    }
    /**
     * Get the proyecto_financiado that owns the Requerimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto_financiado(): BelongsTo
    {
        return $this->belongsTo(ProyectoFinanciado::class, 'id_proy_financiado', 'id_proy_financiado');
    }
    /**
     * Get the estado_requerimiento that owns the Requerimiento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estado_requerimiento(): BelongsTo
    {
        return $this->belongsTo(EstadoRequerimiento::class, 'id_estado_req', 'id_estado_req');
    }



}
