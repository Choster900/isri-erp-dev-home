<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoActivoFijo extends Model
{
    use HasFactory;

    protected $table = 'estado_activo_fijo';
    protected $primaryKey = 'id_estado_af';
    public $timestamps = false;

    protected $fillable = [
        'nombre_estado_af',
        'estado_estado_af',
        'fecha_reg_estado_af',
    ];
}
