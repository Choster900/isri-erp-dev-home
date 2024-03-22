<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoPerc extends Model
{
    use HasFactory;

    protected $table = 'catalogo_perc';
    protected $primaryKey = 'id_catalogo_perc';
    public $timestamps = false;

    protected $fillable = [
        'id_padre_ccta_nicsp',
        'codigo_catalogo_perc',
        'codigo_anterior_catalogo_perc',
        'nombre_catalogo_perc',
        'estado_catalogo_perc',
    ];
}
