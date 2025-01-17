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
        'pensionado_empleado',
        'numero_pension_empleado',
        'id_estado_empleado',
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
        return $this->hasOne('App\Models\Persona', 'id_persona', 'id_persona');
    }

    public function titulo_profesional()
    {
        return $this->hasOne('App\Models\TituloProfesional', 'id_titulo_profesional', 'id_titulo_profesional');
    }

    public function plazas_asignadas()
    {
        return $this->hasMany(PlazaAsignada::class, 'id_empleado', 'id_empleado');
    }

    /**
     * The function "primer_centro_atencion" returns the "Centro Atencion" where the employee
     * was registered first.
     */
    public function primer_centro_atencion()
    {
        return $this->hasOne(PlazaAsignada::class, 'id_empleado', 'id_empleado')
            ->with('centro_atencion')
            ->where('estado_plaza_asignada', 1)
            ->orderBy('fecha_plaza_asignada', 'ASC');
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

    public function permisos()
    {
        return $this->hasMany('App\Models\Permiso', 'id_empleado', 'id_empleado');
    }

    public function evaluaciones_personal(): HasMany
    {
        return $this->hasMany(EvaluacionPersonal::class, 'id_empleado', 'id_empleado');
    }

    public function periodos_laboral()
    {
        return $this->hasMany(PeriodoLaboral::class, 'id_empleado', 'id_empleado');
    }

    public function tipo_pension()
    {
        return $this->hasOne('App\Models\TipoPension', 'id_tipo_pension', 'id_tipo_pension');
    }

    /**
     * The function "finiquitos_empleado" returns a collection of "FiniquitoLaboral" models associated
     * with a specific employee.
     * 
     * @return HasMany a HasMany relationship.
     */
    public function finiquitos_empleado(): HasMany
    {
        return $this->hasMany(FiniquitoLaboral::class, 'id_empleado', 'id_empleado');
    }
}
