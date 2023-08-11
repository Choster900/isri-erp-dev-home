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
    ];

    public function niveles_educativos()
    {
        return $this->hasMany(NivelEducativo::class,'id_tipo_nivel_educativo','id_tipo_nivel_educativo');
    }
}
