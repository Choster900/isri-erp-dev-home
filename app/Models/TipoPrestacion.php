<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPrestacion extends Model
{
    use HasFactory;
    
    protected $table = 'tipo_prestacion';
    protected $primaryKey = 'id_tipo_prestacion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_prestacion',
    ];
}
