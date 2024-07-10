<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoProcesoCompra extends Model
{
    use HasFactory;

    protected $table = 'tipo_proceso_compra';
    protected $primaryKey = 'id_tipo_proceso_compra';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_proceso_compra',
    ];

    /**
     * Get all of the procesos_compra for the TipoProcesoCompra
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function procesos_compra(): HasMany
    {
        return $this->hasMany(ProcesoCompra::class, 'id_tipo_proceso_compra', 'id_tipo_proceso_compra');
    }
}
