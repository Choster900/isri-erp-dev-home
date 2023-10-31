<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlujoControl extends Model
{
    use HasFactory;

    protected $table = 'flujo_control';
    protected $primaryKey = 'id_flujo_control';
    public $timestamps = false;

    protected $fillable = [
        'id_persona_etapa',
        'id_tipo_flujo_control',
        'orden_flujo_control',
        'estado_flujo_control',
    ];
}
