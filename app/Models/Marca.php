<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marca';
    protected $primaryKey = 'id_marca';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_marca',
        'nombre_marca',
        'fecha_reg_marca',
        'fecha_mod_marca',
        'estado_marca',
        'ip_marca',
        'usuario_marca'
    ];

    public function modelos()
    {
        return $this->hasMany('App\Models\Modelo','id_marca','id_marca');
    }
    /**
     * Get all of the comments for the Marca
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productos_adquisiciones(): HasMany
    {
        return $this->hasMany(ProductoAdquisicion::class, 'id_marca', 'id_marca');
    }
    /**
     * Get the TipoMarca model associated with this instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo_marca()
    { //                                               FK                PK
        return $this->belongsTo(TipoMarca::class, 'id_tipo_marca', 'id_tipo_marca');
    }
}
