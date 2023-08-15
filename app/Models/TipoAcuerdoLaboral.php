<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoAcuerdoLaboral extends Model
{
    use HasFactory;


    protected $table = 'tipo_acuerdo_laboral';
    protected $primaryKey = 'id_tipo_acuerdo_laboral';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_acuerdo_laboral',
        'estado_tipo_acuerdo_laboral',
        'fecha_reg_tipo_acuerdo_laboral',
        'fecha_mod_tipo_acuerdo_laboral',
        'usuario_tipo_acuerdo_laboral',
        'ip_tipo_acuerdo_laboral',  
    ];

    /**
     * Get all of the acuerdo_laboral for the TipoAcuerdoLaboral
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acuerdo_laboral(): HasMany
    {
        return $this->hasMany(AcuerdoLaboral::class, 'id_tipo_acuerdo_laboral', 'id_tipo_acuerdo_laboral');
    }
    public function scopeWithAcuerdoLaboral()
    {
        return $this->hasManyThrough(TipoAcuerdoLaboral::class, AcuerdoLaboral::class);
    }
}