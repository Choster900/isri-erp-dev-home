<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesion extends Model
{
    use HasFactory;

    protected $table = 'profesion';
    protected $primaryKey = 'id_profesion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_profesion',
        'id_profesion_rnpn',
        'nombre_profesion_rnpn',
        //'estado_profesion',
        //'fecha_reg_profesion',
        //'fecha_mod_profesion',
        //'usuario_profesion',
        //'ip_profesion',
    ];
}
