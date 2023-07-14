<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Familiar extends Model
{
    use HasFactory;

    protected $table = 'familiar';
    protected $primaryKey = 'id_familiar';
    public $timestamps = false;

    protected $fillable = [
        'id_parentesco',
        'id_persona',
        'nombre_familiar',
        'beneficiado_familiar',
        'porcentaje_familiar',
        'estado_familiar',
        'fecha_reg_familiar',
        'fecha_mod_familiar',
        'usuario_familiar',
        'ip_familiar'
    ];
    /**
     * Get the user that owns the Familiar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentesco(): BelongsTo
    {
        return $this->belongsTo(Parentesco::class, 'id_parentesco', 'id_parentesco');
    }
    /**
     * Get the user that owns the Familiar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }
}