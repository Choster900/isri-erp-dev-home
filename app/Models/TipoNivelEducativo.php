<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoNivelEducativo extends Model
{
    use HasFactory;

    protected $table = 'tipo_nivel_educativo';
    protected $primaryKey = 'id_tipo_nivel_educativo';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_nivel_educativo',
        //'estado_tipo_nivel_educativo',
        //'fecha_tipo_nivel_educativo',
        //'fecha_tipo_nivel_educativo',
        //'usuario_tipo_nivel_educativo',
        //'ip_tipo_nivel_educativo',
    ];

    public function niveles_educativos()
    {
        return $this->hasMany('App\Models\NivelEducativo','id_tipo_nivel_educativo','id_tipo_nivel_educativo');
    }
}
