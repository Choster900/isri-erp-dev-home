<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoCivil extends Model
{
    use HasFactory;
    
    protected $table = 'estado_civil';
    protected $primaryKey = 'id_estado_civil';
    public $timestamps = false;

    protected $fillable = [
        'nombre_estado_civil',
        //'estado_estado_civil',
        'fecha_reg_estado_civil',
        'fecha_mod_estado_civil',
        'usuario_estado_civil',
        'ip_estado_civil',
    ];
}
