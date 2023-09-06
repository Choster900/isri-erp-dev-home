<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    use HasFactory;
    
    protected $table = 'empleado';
    protected $primaryKey = 'id_empleado';
    public $timestamps = false;

    protected $fillable = [
        'id_persona',
        'id_tipo_pension',
        'id_banco',
        'id_titulo_profesional',
        'codigo_empleado',
        'nup_empleado',
        'isss_empleado',
        'cuenta_banco_empleado',
        'fecha_contratacion_empleado',
        'email_institucional_empleado',
        'email_alternativo_empleado',
        'estado_empleado',
        'fecha_reg_empleado',
        'fecha_mod_empleado',
        'usuario_empleado',
        'ip_empleado'
    ];

    public function persona()
    {
        return $this->hasOne('App\Models\Persona','id_persona','id_persona');
    }

    public function plazas_asignadas()
    {
        return $this->hasMany(PlazaAsignada::class,'id_empleado','id_empleado');
    }

    /**
     * Get all of the acuerdos_laborales for the Empleado
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acuerdo_laboral(): HasMany
    {
        return $this->hasMany(AcuerdoLaboral::class, 'id_empleado', 'id_empleado');
    }
    /**
     * Get all of the evaluaciones_personal for the Empleado
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evaluaciones_personal(): HasMany
    {
        return $this->hasMany(EvaluacionPersonal::class, 'id_empleado', 'id_empleado');
    }
}
