<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamento';
    protected $primaryKey = 'id_departamento';
    protected $casts = [ 'id_departamento' => 'string' ];

    public $timestamps = false;

    protected $fillable = [
        'id_pais',
        'nombre_departamento',
        'codigo_admon_departamento',
        'nit_departamento',
    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class,'id_pais','id_pais');
    }

    public function municipios()
    { 
        return $this->hasMany(Municipio::class,'id_departamento','id_departamento');
    }
}
