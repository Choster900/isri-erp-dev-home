<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoCivil extends Model
{
    use HasFactory;
    
    protected $table = 'estado_civil';
    protected $primaryKey = 'id_estado_civil';
    public $timestamps = false;

    protected $fillable = [
        'nombre_estado_civil',
        'fecha_reg_estado_civil',
        'fecha_mod_estado_civil',
        'usuario_estado_civil',
        'ip_estado_civil',
    ];
    /**
     * Get all of the personas for the EstadoCivil
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class, 'id_estado_civil', 'id_estado_civil');
    }
}
