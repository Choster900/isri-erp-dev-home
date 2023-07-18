<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    use HasFactory;

    protected $table = 'genero';
    protected $primaryKey = 'id_genero';
    public $timestamps = false;

    protected $fillable = [
        'nombre_genero',
        //'estado_genero',
        //'fecha_reg_genero',
        //'fecha_mod_genero',
        //'usuario_genero',
        //'ip_genero',
    ];
}
