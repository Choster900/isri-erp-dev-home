<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcuerdoLaboral extends Model
{
    use HasFactory;


    protected $table = 'acuerdo_laboral';
    protected $primaryKey = 'id_acuerdo_laboral';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_acuerdo_laboral',
        'id_empleado',
        'fecha_acuerdo_laboral',
        'oficio_acuerdo_laboral',
        'comentario_acuerdo_laboral',
        'estado_acuerdo_laboral',
        'fecha_inicio_acuerdo_laboral',
        'fecha_fin_acuerdo_laboral',
        'fecha_reg_acuerdo_laboral',
        'fecha_mod_acuerdo_laboral',
        'usuario_acuerdo_laboral',
        'ip_acuerdo_laboral',
    ];
    /**
     * Get the tipo_acuerdo_laboral that owns the AcuerdoLaboral
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo_acuerdo_laboral(): BelongsTo
    {
        return $this->belongsTo(TipoAcuerdoLaboral::class, 'id_tipo_acuerdo_laboral', 'id_tipo_acuerdo_laboral');
    }
    public function scopeWithTipoAcuerdoLaboral($query)
    {
        return $query->with("tipo_acuerdo_laboral");
    }
    /**
     * Get the empleados that owns the AcuerdoLaboral
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empleados(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }
}