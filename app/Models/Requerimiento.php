<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requerimiento extends Model
{
    use HasFactory;
    protected $table = 'requerimiento';
    protected $primaryKey = 'id_requerimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_lt',
        'id_centro_atencion',
        'id_proy_financiado',
        'id_estado_req',
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


}
