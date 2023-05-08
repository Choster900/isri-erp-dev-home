<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marca';
    protected $primaryKey = 'id_marca';
    public $timestamps = false;

    protected $fillable = [
        'nombre_marca',
        'fecha_reg_marca',
        'fecha_mod_marca',
        'estado_marca',
        'ip_marca',
        'usuario_marca'
    ];

    public function modelos()
    {
        return $this->hasMany('App\Models\Modelo','id_marca','id_marca');
    }
}
