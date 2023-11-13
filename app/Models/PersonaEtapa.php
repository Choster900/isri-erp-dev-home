<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaEtapa extends Model
{
    use HasFactory;

    protected $table = 'persona_etapa';
    protected $primaryKey = 'id_persona_etapa';
    public $timestamps = false;

    protected $fillable = [
        'persona_etapa',
        'estado_persona_etapa',
        'fecha_reg_persona_etapa',
        'fecha_mod_persona_etapa',
        'usuario_persona_etapa',
        'ip_persona_etapa',
    ];
}
