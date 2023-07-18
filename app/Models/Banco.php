<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;
    
    protected $table = 'banco';
    protected $primaryKey = 'id_banco';
    public $timestamps = false;

    protected $fillable = [
        'nombre_banco',
        'codigo_banco',
        'estado_banco',
        'fecha_reg_banco',
        'fecha_mod_banco',
        'usuario_banco',
        'ip_banco',
    ];
}
