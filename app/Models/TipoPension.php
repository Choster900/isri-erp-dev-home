<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPension extends Model
{
    use HasFactory;
 
    protected $table = 'tipo_pension';
    protected $primaryKey = 'id_tipo_pension';
    public $timestamps = false;

    protected $fillable = [
        'codigo_tipo_pension',
        'nombre_tipo_pension',
        //'estado_tipo_pension',
        'fecha_reg_tipo_pension',
        'fecha_mod_tipo_pension',
        'usuario_tipo_pension',
        'ip_tipo_pension',
    ];
}
