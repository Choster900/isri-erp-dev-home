<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProyectoFinanciado extends Model
{
    use HasFactory;

    protected $table = 'proyecto_financiado';
    protected $primaryKey = 'id_proy_financiado';
    public $timestamps = false;

    protected $fillable = [
        'id_fuente_fmto',
        'codigo_proy_financiado',
        'nombre_proy_financiado',
        'estado_proy_financiado',
        'fecha_reg_proy_financiado',
        'fecha_mod_proy_financiado',
        'ip_proy_financiado',
        'usuario_proy_financiado'
    ];

    /**
     * Get all of the Quedan for the ProyectoFinanciado
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Quedan(): HasMany
    {
        return $this->hasMany(Quedan::class, 'id_proy_financiado', 'id_proy_financiado');
    }

    public function conceptos_ingreso()
    {
        return $this->hasMany('App\Models\ConceptoIngreso', 'id_proy_financiado', 'id_proy_financiado');
    }
}
