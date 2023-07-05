<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Persona extends Model
{
    use HasFactory;
    protected $table = 'persona';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $fillable = [
        'id_empleado',
        'id_nivel_educativo',
        'id_profesion',
        'id_usuario',
        'id_municipio',
        'id_aspirante',
        'id_genero',
        'id_estado_civil',
        'dui_persona',
        'pnombre_persona',
        'snombre_persona',
        'tnombre_persona',
        'papellido_persona',
        'sapellido_persona',
        'tapellido_persona',
        'fecha_nac_persona',
        'nombre_madre_persona',
        'nombre_padre_persona',
        'nombre_conyuge_persona',
        'telefono_persona',
        'email_persona',
        'peso_persona',
        'estatura_persona',
        'observacion_persona',
        'estado_persona',
        'fecha_reg_persona',
        'fecha_mod_persona',
        'usuario_persona',
        'ip_persona',

    ];

    public function empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id_persona', 'id_persona');
    }
    /**
     * Get all of the familiares for the Persona
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function familiar(): HasMany
    {
        return $this->hasMany(Familiar::class, 'id_persona', 'id_persona');
    }

}