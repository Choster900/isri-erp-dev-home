<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $table = 'municipio';
    protected $primaryKey = 'id_municipio';
    public $timestamps = false;

    protected $fillable = [
        'id_departamento',
        'nombre_municipio',
        'codigo_admon_municipio',
        //'estado_municipio',
        //'fecha_reg_municipio',
        //'fecha_mod_municipio',
        //'usuario_municipio',
        //'ip_municipio',
    ];

    public function departamento()
    {
        return $this->belongsTo('App\Models\Departamento','id_departamento','id_departamento');
    }
}
