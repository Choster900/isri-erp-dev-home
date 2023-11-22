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
        return $this->hasOne(Empleado::class, 'id_persona', 'id_persona');
    }
    public function usuario()
    {
        return $this->hasOne('App\Models\User', 'id_usuario', 'id_persona');
    }
    /**
     * Get the genero that owns the Persona
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function genero(): BelongsTo
    {
        return $this->belongsTo(Genero::class, 'id_genero', 'id_genero');
    }
    public function residencias()
    {
        return $this->hasMany('App\Models\Residencia', 'id_persona', 'id_persona');
    }
    public function fotos()
    {
        return $this->hasMany(Foto::class, 'id_persona', 'id_persona');
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

    /**
     * Get the profesiones that owns the Empleado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profesion(): BelongsTo
    {
        return $this->belongsTo(Profesion::class, 'id_profesion', 'id_profesion');
    }
    /**
     * Get the nivel_educativo that owns the Persona
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nivel_educativo(): BelongsTo
    {
        return $this->belongsTo(NivelEducativo::class, 'id_nivel_educativo', 'id_nivel_educativo');
    }
    /**
     * Get the estado_civil that owns the Persona
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estado_civil(): BelongsTo
    {
        return $this->belongsTo(EstadoCivil::class, 'id_estado_civil', 'id_estado_civil');
    }
    /**
     * Get the municipio that owns the Persona
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'id_municipio', 'id_municipio');
    }
    /**
     * Get all of the archivos_anexos for the Persona
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function archivo_anexo(): HasMany
    {
        return $this->hasMany(ArchivoAnexo::class, 'id_persona', 'id_persona');
    }
}
