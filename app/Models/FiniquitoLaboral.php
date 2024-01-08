<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FiniquitoLaboral extends Model
{
    use HasFactory;

    protected $table = 'finiquito_laboral';
    protected $primaryKey = 'id_finiquito_laboral';
    public $timestamps = false;

    protected $fillable = [
        'id_empleado',
        'id_persona',
        'id_ejercicio_fiscal',
        'monto_finiquito_laboral',
        'fecha_firma_finiquito_laboral',
        'hora_firma_finiquito_laboral',
        'firmado_finiquito_laboral',
        'fecha_reg_finiquito_laboral',
        'fecha_mod_finiquito_laboral',
        'usuario_finiquito_laboral',
        'ip_finiquito_laboral',
    ];

    /**
     * The function "empleado" returns a BelongsTo relationship between the current model and the
     * "Empleado" model, using the "id_empleado" column as the foreign key.
     * 
     * @return BelongsTo a BelongsTo relationship.
     */
    public function empleado() : BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    /**
     * The function "persona" returns a BelongsTo relationship between the current model and the
     * Persona model, using the "id_persona" column as the foreign key.
     * 
     * @return BelongsTo a BelongsTo relationship.
     */
    public function persona() : BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }
}
