<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EjercicioFiscal extends Model
{
    use HasFactory;

    protected $table = 'ejercicio_fiscal';
    protected $primaryKey = 'id_ejercicio_fiscal';
    public $timestamps = false;

    protected $fillable = [
        'id_estado_ef',
        'id_estado_finiquito',
        'ejercicio_fiscal',
        'fecha_reg_ejercicio_fiscal',
        'fecha_mod_ejercicio_fiscal',
        'monto_liq_ejercicio_fiscal',
    ];

    /**
     * The function "estado_ejercicio_fiscal" returns a BelongsTo relationship with the
     * "EstadoEjercicioLaboral" model, using the foreign key "id_estado_ef" and the local key
     * "id_estado_ef".
     * 
     * @return BelongsTo a BelongsTo relationship.
     */
    public function estado_ejercicio_fiscal() : BelongsTo
    {
        return $this->belongsTo(EstadoEjercicioLaboral::class, 'id_estado_ef', 'id_estado_ef');
    }

    public function finiquitos_ejercicio_fiscal() : HasMany
    {
        return $this->hasMany(FiniquitoLaboral::class, 'id_ejercicio_fiscal', 'id_ejercicio_fiscal');
    }
}
