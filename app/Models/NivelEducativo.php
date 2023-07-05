<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelEducativo extends Model
{
    use HasFactory;

    protected $table = 'nivel_educativo';
    protected $primaryKey = 'id_nivel_ejecutivo';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_nivel_educativo',
        'nombre_nivel_educativo',
        'fecha_reg_nivel_educativo',
        'fecha_mod_nivel_educativo',
        //'estado_nivel_educativo',
        'usuario_nivel_educativo',
        'ip_nivel_educativo',
    ];

    public function tipo_nivel_educativo()
    {
        return $this->belongsTo('App\Models\TipoNivelEducativo','id_tipo_nivel_educativo','id_tipo_nivel_educativo');
    }
}
