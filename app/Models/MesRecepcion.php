<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesRecepcion extends Model
{
    use HasFactory;

    protected $table = 'mes_recepcion';
    protected $primaryKey = 'id_mes_recepcion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_mes_recepcion',
        'codigo_mes_recepcion',
    ];
}
