<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMarca extends Model
{
    use HasFactory;

    protected $table = 'tipo_marca';
    protected $primaryKey = 'id_tipo_marca';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_marca',
    ];
}
