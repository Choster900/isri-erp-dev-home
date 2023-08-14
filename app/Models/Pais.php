<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;

    protected $table = 'pais';
    protected $primaryKey = 'id_pais';
    protected $casts = [ 'id_pais' => 'string' ];

    public $timestamps = false;

    protected $fillable = [
        'nombre_pais',
        'ciudadania_pais',
        'codigo_2digitos_pais',
        'codigo_telefono_pais',
        'dominio_web_pais',
        'codigo_mh_pais',
        //'estado_pais',
        //'fecha_reg_pais',
        //'fecha_mod_pais',
        //'usuario_pais',
        //'ip_pais',
    ];

    public function departamentos()
    { 
        return $this->hasMany(Departamento::class,'id_pais','id_pais');
    }
}
