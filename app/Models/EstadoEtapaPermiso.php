<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoEtapaPermiso extends Model
{
    use HasFactory;

    protected $table = 'estado_etapa_permiso';
    protected $primaryKey = 'id_estado_etapa_permiso';
    public $timestamps = false;

    protected $fillable = [
        'nombre_estado_etapa_permiso',
        'estado_estado_etapa_permiso',
    ];
}
