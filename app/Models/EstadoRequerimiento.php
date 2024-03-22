<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoRequerimiento extends Model
{
    protected $table = 'estado_requerimiento';
    protected $primaryKey = 'id_estado_req';
    public $timestamps = false;

    protected $fillable = [
        'nombre_estado_req'
    ];
}
