<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SerieRetencionIva extends Model
{
    use HasFactory;
    protected $table = 'serie_retencion_iva';
    protected $primaryKey = 'id_serie_retencion_iva';
    public $timestamps = false;

    protected $fillable = [
        'inicio_serie_retencion_iva',
        'fin_serie_retencion_iva',
        'serie_retencion_iva',
        'resolucion_serie_retencion_iva',
        'fecha_serie_retencion_iva',
        'estado_serie_retencion_iva',
        'fecha_reg_serie_retencion_iva',
        'fecha_mod_serie_retencion_iva',
        'usuario_serie_retencion_iva',
        'ip_serie_retencion_iva',
    ];

    /**
     * Get all of the comments for the SerieRetencionIva
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Quedan(): HasOne
    {
        return $this->hasOne(Quedan::class, 'id_serie_retencion_iva', 'id_serie_retencion_iva');
    }
}