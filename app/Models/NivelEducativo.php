<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NivelEducativo extends Model
{
    use HasFactory;

    protected $table = 'nivel_educativo';
    protected $primaryKey = 'id_nivel_ejecutivo';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_nivel_edu',
        'nombre_nivel_educativo',
        'fecha_reg_nivel_educativo',
        'fecha_mod_nivel_educativo',
        'usuario_nivel_educativo',
        'ip_nivel_educativo',
    ];

    public function tipo_nivel_educativo()
    {
        return $this->belongsTo(TipoNivelEducativo::class,'id_tipo_nivel_edu','id_tipo_nivel_edu');
    }
    /**
     * Get all of the personas for the NivelEducativo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class,'id_nivel_educativo', 'id_nivel_educativo');
    }
}
