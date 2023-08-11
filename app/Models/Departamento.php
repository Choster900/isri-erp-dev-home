<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamento';
    protected $primaryKey = 'id_departamento';
    public $timestamps = false;

    protected $fillable = [
        'id_pais',
        'nombre_departamento',
        'codigo_admon_departamento',
        'nit_departamento',
    ];

    public function pais()
    {
        return $this->belongsTo('App\Models\Pais','id_pais','id_pais');
    }

    public function municipios()
    { 
        return $this->hasMany('App\Models\Municipio','id_departamento','id_departamento');
    }
}
