<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoQuedan extends Model
{
    use HasFactory;

    protected $table = 'estado_quedan';
    protected $primaryKey = 'id_estado_quedan';
    public $timestamps = false;

    protected $fillable = [
        'nombre_estado_quedan',
    ];
}
