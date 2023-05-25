<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BienEspecifico extends Model
{
    use HasFactory;

    protected $table = 'bien_especifico';
    protected $primaryKey = 'id_bien_especifico';
    public $timestamps = false;

    protected $fillable = [
        'id_ccta_presupuestal',
        'nombre_bien_especifico',
        'descripcion_bien_especifico',
        'estado_bien_especifico',
        'fecha_reg_bien_especifico',
        'fecha_mod_bien_especifico',
        'usuario_bien_especifico',
        'ip_bien_especifico',
    ];

}
